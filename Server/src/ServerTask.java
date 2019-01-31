import filesystem.FileWriter;
import model.Measurement;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.Socket;
import java.util.Arrays;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

// Handles server functions
public class ServerTask extends Thread {

    // Generator settings (amount of stations and measurements)
    private final int AMOUNT_STATIONS = 10;         // in each <weatherdata>
    private final int AMOUNT_MEASUREMENTS = 18;    // in each <measurement>
    private final int MAX_BACKLOG = 30;            // Amount of saved values (for calculations and corrections)
    private final int MAX_TEMP_DIFFERENCE = 20;
    private final int TEMP_DIFFERENCE_OFFSET = 2;

    private Socket socket = null;
    Pattern regex = Pattern.compile("(>)(?<value>.*)(<)", Pattern.MULTILINE);

    // Multidimensional array containing stations, measurements and data
    float stationData[][][] = new float[MAX_BACKLOG][AMOUNT_STATIONS][AMOUNT_MEASUREMENTS];
    int currentStation = 0;
    int currentMeasurement = 0;
    int currentBacklog = 0;

    // Use previous data if no data is avaible. (Can't be calculated!)
    private final int use_previous[] = {
            0,  // Station number
            15  // FRSHTT (events, binary)
    };

    // Temperature index, (To know if we need to calculate average or extrapolate)
    private final int temp_id = 7;


    private int timeout = 0;
    private int maxTimeout = 100;

    private boolean isStarted = false;
    private boolean isMeasuring = false;
    private boolean writeData = false;
    private int writeSec = 10;

    private int writeBacklog = 0;
    String input = "";

    /**
     * Constructor
     * @param socket
     */
    public ServerTask(Socket socket) {
        this.socket = socket;

        // Fill array
        for (float[][] x : stationData) {
            for (float[] y : x) {
                Arrays.fill(y, 0);
            }
        }
    }


    /**
     * Start thread
     */
    public void run() {

        try {
            BufferedReader reader = new BufferedReader(new InputStreamReader(socket.getInputStream()));


            // Infinite loop to prevent thread from stopping
            while(true){

                // Read line (input)
                input = reader.readLine();


                if(input != null){
                    // Remove spaces from the input
                    input = input.replaceAll("\\s","");
                    checkInput(input);
                }else{
                    timeout++;
                    if(timeout >= maxTimeout){
                        System.out.println("Received no weatherdata for more then 100 times");
                        break;
                    }
                }
            }
        }
        catch (IOException e) {
            System.out.println("Error handling client ");
        }
        finally {
            try {
                //System.out.println("Closing socket");

                socket.close();
            } catch (IOException e) {
                System.out.println("Couldn't close socket");
            }
        }
    }

    private void checkInput(String input){
        // Are we started?
        if(isStarted){
            // Are we measuring
            if(isMeasuring){
                // If it's the end a measurement stop
                if(input.equals("</MEASUREMENT>")){
                    isMeasuring = false;
                    currentMeasurement= 0;

                    currentStation++;
                    // Increase index
                }else{
                    // Process data
                   handleInput(input);
                }

            }else if(input.equals("<MEASUREMENT>")){
                isMeasuring = true;
            }


            // Stop reading weatherdata / reset
            if(input.equals("</WEATHERDATA>")){
                isStarted = false;
                currentMeasurement = 0;
                currentBacklog++;

                if(writeData){
                    exportData();
                }

                // Reset backlog index
                if(currentBacklog >= MAX_BACKLOG){
                    currentBacklog = 0;
                }
            }else{

            }
        }else if(input.equals("<WEATHERDATA>")){
            isStarted = true;
            // System.out.println("Start processing");
            timeout = 0;
            currentStation = 0;
        }
    }

    private void handleInput(String input){
        String desc = input;
        input = ParseData(input);

        if(!input.equals("")){
            if(currentMeasurement == 1 || currentMeasurement == 4){
                if(input.contains("-")){
                    String res[] = input.split("-");
                    processArray(res);
                }else if(input.contains(":")){
                    String res[] = input.split(":");
                    processArray(res);

                    if(!writeData){
                        int time =(int)stationData[currentBacklog][currentStation][currentMeasurement-1];

                        if(time % writeSec == 0){
                            writeData = true;
                            writeBacklog = currentBacklog;
                            //System.out.println("SAVING ON TIME: " + time);
                        }
                    }
                }
            }else{
                //System.out.println(currentMeasurement);
                // Parse value
                processInput(Float.parseFloat(input));
            }


        }else{
            // Recover missing value
            boolean usePrevious = false;
            float newVariable = 0;

            // No data, check if we can use previous data:
            for(int d = 0; d < use_previous.length; d++){
                if(currentMeasurement == use_previous[d]){
                    // use previous data
                    usePrevious = true;
                    newVariable = getPreviousData();
                    break;
                }
            }

            // Extrapolate Variable!
            if(!usePrevious){
                newVariable = extrapolateCurrentValue();
            }

            processInput(newVariable);
        }
    }

    /**
     * Extrapolates current value based on previous measurements.
     */
    private float extrapolateCurrentValue() {
        // TODO:
        float temp = stationData[currentBacklog][currentStation][currentMeasurement];
        if(stationData.length > 1) {
            float bT = stationData[currentBacklog][currentStation][currentMeasurement];
            float eT = stationData[currentBacklog - (stationData.length > MAX_BACKLOG ? MAX_BACKLOG : stationData.length -1)][currentStation][currentMeasurement];

            float x = eT - bT;
            temp = (x / (stationData.length - 1) + eT);
            System.out.println(String.format("begin: %.1f   -----   end: %.1f     extra: %.3f", bT, eT, temp));
        }
        else {
            System.out.println(String.format("begin: %.1f   -----   end: %.1f     extra: %.1f", temp, temp, temp));
        }
        return temp;
        //System.out.println("This value needs to be extrapolated, not implemented!");
        //return 0.0f;
    }

    private float getPreviousData(){
        int backlogToUse = currentBacklog - 1;

        if(backlogToUse < 0){
            backlogToUse = MAX_BACKLOG - 1;
        }

        // Return the most recent value!
        return stationData[backlogToUse][currentStation][currentMeasurement];
    }


    private void processArray(String data[]){
        for(int x = 0; x < data.length; x++){
            processInput(Float.parseFloat(data[x]));
        }
    }

    private  void processInput(float data){
        // If not temp, append to history
        if(currentMeasurement != temp_id){
            stationData[currentBacklog][currentStation][currentMeasurement] = data;
        }else{
            // TODO: Valide with 20% offset

            System.out.println("Validate 20% offset of temperature! (Keep in mind the ranges for the 20%)");

            //if NOT within range
            // data = extrapolateCurrentValue();
            if(stationData.length > 1) {
                float diff = Math.abs(data - stationData[currentBacklog-1][currentStation][currentMeasurement]);
                if(diff > TEMP_DIFFERENCE_OFFSET) {
                    if ((stationData[currentBacklog - 1][currentStation][currentMeasurement] / data) * 100 <= MAX_TEMP_DIFFERENCE) {
                        stationData[currentBacklog][currentStation][currentMeasurement] = data;
                    }
                    else {
                        stationData[currentBacklog][currentStation][currentMeasurement] = extrapolateCurrentValue();
                    }
                }
                else {
                    stationData[currentBacklog][currentStation][currentMeasurement] = data;
                }
            }
            else {
                stationData[currentBacklog][currentStation][currentMeasurement] = data;
            }
        }

        // Increment current measure index
        currentMeasurement++;
    }

    private String ParseData(String input){

        Matcher m = regex.matcher(input);

        if(m.find())
        {
            // Prepare result
            //result[0] = m.group("tag");     // Assign tag
            //System.out.println(input);
            return m.group("value");   // Assign value

        }
        // Missing
        return "";
    }

    private void exportData(){
        writeData = false;
        FileWriter.addMeasurements(stationData[writeBacklog]);
        //FileWriter.addMeasurements(stationData[writeBacklog].clone());
    }
}
