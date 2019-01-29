import filesystem.FileWriter;
import model.Measurement;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.Socket;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

// Handles server functions
public class ServerTask extends Thread {

    // Generator settings (amount of stations and measurements)
    private final int amount_stations = 10;         // in each <weatherdata>
    private  final int amount_measurements = 14;    // in each <measurement>
    private  final int max_backlog = 30;            // Amount of saved values (for calculations and corrections)

    private Socket socket = null;;
    private XMLParser parser = null;
    private FileWriter writer;
    Pattern regex = Pattern.compile("(>)(?<value>.*)(<)", Pattern.MULTILINE);

    // Multidimensional array containing stations, measurements and data
    float stationData[][][] = new float[amount_stations][amount_measurements][max_backlog];
    int currentStation = 0;
    int currentMeasurement = 0;
    int currentBacklog = 0;

    // Use previous data if no data is avaible. (Can't be calculated!)
    private final int use_previous[] = {
            0,  // Station number
            1,  // Date
            2,  // Time
            11, // FRSHTT (events, binary)
    };

    // Temperature index, (To know if we need to calculate average or extrapolate)
    private final int temp_id = 3;


    private int timeout = 0;
    private int maxTimeout = 100;

    private boolean isStarted = false;
    private boolean isMeasuring = false;

    /**
     * Constructor
     * @param socket
     */
    public ServerTask(Socket socket, FileWriter writer) {
        parser = new XMLParser();
        this.socket = socket;
        this.writer = writer;

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

            // Create data container
            List<String> measurementData = new ArrayList<>();

            boolean isReading = false;

            String input = "";
            int currentSecond = -1;
            int savePerSecond = 10;


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
                currentStation++;
                currentMeasurement = 0;
                currentBacklog++;

                // Reset backlog index
                if(currentBacklog >= max_backlog){
                    currentBacklog = 0;
                }
            }else{

            }
        }else if(input.equals("<WEATHERDATA>")){
            isStarted = true;
            // System.out.println("Start processing");
            timeout = 0;
        }
    }

    private void handleInput(String input){
        input = ParseData(input);

        if(!input.equals("")){
            if(currentMeasurement == 1 || currentMeasurement == 4){
                if(input.contains("-")){
                    String res[] = input.split("-");
                    processArray(res);
                }else if(input.contains(":")){
                    String res[] = input.split(":");
                    processArray(res);
                }
            }else{
                //System.out.println(currentMeasurement);
                // Parse value
                processInput(Float.parseFloat(input));
            }


        }else{
            //System.out.println("Null data, check old values / extrapolate!");
        }
    }

    private void processArray(String data[]){
        for(int x = 0; x < data.length; x++){
            processInput(Float.parseFloat(data[x]));
        }
    }

    private  void processInput(float data){
        // If it's temperature ID then check 20% offset
        // else append data to backlog

        stationData[currentStation][currentMeasurement][currentBacklog] = data;

        currentMeasurement++;
    }

    public String ParseData(String input){

        Matcher m = regex.matcher(input);

        if(m.find())
        {
            // Prepare result
            //result[0] = m.group("tag");     // Assign tag
            return m.group("value");   // Assign value

        }
        // Missing
        return "";
    }
}
