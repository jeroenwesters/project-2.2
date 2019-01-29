import filesystem.FileWriter;
import model.Measurement;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.Socket;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

// Handles server functions
public class ServerTask extends Thread {

    // Generator settings (amount of stations and measurements)
    private final int amount_stations = 10;         // in each <weatherdata>
    private  final int amount_measurements = 14;    // in each <measurement>
    private  final int max_backlog = 30;            // Amount of saved values (for calculations and corrections)

    private Socket socket = null;;
    private XMLParser parser = null;
    private FileWriter writer;

    // Multidimensional array containing stations, measurements and data
    String stationData[][][] = new String[amount_stations][amount_measurements][max_backlog];
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
    private int maxTimeout = 0;

    /**
     * Constructor
     * @param socket
     */
    public ServerTask(Socket socket, FileWriter writer) {
        parser = new XMLParser();
        this.socket = socket;
        this.writer = writer;

        // Fill array
        for (String[][] x : stationData) {
            for (String[] y : x) {
                Arrays.fill(y, "0");
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

                // If there is no input, cancel
                if(input == null){
                    // Remove spaces from the input
                    input = input.replaceAll("\\s","");


                    // Check if the file starts or ends!
                    if(input.equals("<WEATHERDATA>")){

                        // Set current station index to 0
                        currentStation = 0;

                    }else if (input.equals("</WEATHERDATA>")){
                        // End of file


                        // Add new value to our backlog
                        currentBacklog++;

                        // If we have reached the end of the backlog, start over!
                        if(currentBacklog >= max_backlog){
                            currentBacklog = 0;
                        }
                    }


                    if(!isReading){
                        // Look for Measurement start

                        if(input.equals("<MEASUREMENT>")){
                            // Clear measurements!
                            measurementData.clear();

                            // Start reading the file
                            isReading = true;
                        }
                    }else if(isReading ){
                        // If it is the end of a measurement
                        if(input.equals("</MEASUREMENT>")){
                            // Continue to next station, reset measurement index
                            currentStation++;
                            currentMeasurement = 0;

                            // Measurement reading!
                            isReading = false;
                            Measurement measurement = Measurement.fromData(measurementData);
                            if(currentSecond != measurement.getTime().getSeconds()) {
                                currentSecond = measurement.getTime().getSeconds();
                            }
                            if(currentSecond % savePerSecond == 0) {
                                this.writer.addMeasurement(measurement);
                            }
                        }else{
                            // Convert string (to get variable)
                            String data = parser.ParseData(input);

                            // Check for zero value
                            if(data.equals("")){
                                boolean usePrevious = false;

                                // No data, check if we can use previous data:
                                for(int d = 0; d < use_previous.length; d++){
                                    if(currentMeasurement == use_previous[d]){
                                        // Array containts this id, so we need to use our previous data!
                                        usePrevious = true;
                                        break;
                                    }
                                }

                                // If true, these variables can't be calculated!
                                if(usePrevious){
                                    // Get previous index!
                                    int backlog = currentBacklog - 1;

                                    // If backlog is in range
                                    if(backlog < 0){
                                        // Go back to the last index of the backlog!
                                        backlog = max_backlog -1;
                                    }

                                    // Assign the data from the backlog
                                    data = stationData[currentStation][currentMeasurement][backlog];
                                    // Debug
                                    System.out.println("New data: " + input + "  " + data);




                                }else{
                                    // Todo: EXTRAPOLATE!
                                    System.out.println("EXTRAPOLATE data: " + input + "  -  " + data);


                                    //data = CorrectMissingData(stationData, currentStation, currentMeasurement, currentBacklog);
                                    // data = "My AVARAGE VALUE";
                                    data = "0";
                                }

                            } if(currentMeasurement == temp_id){
                                //System.out.println("It's a temperature thats not NULL!!!");

                                // Todo: check 20% max difference!

                                // Previous data:
                                int prev = currentBacklog - 1;
                                if(prev < 0){
                                    prev = max_backlog - 1;
                                }

                                // Todo: Move offset 20 to other place!
                                // Validates data on given thresh hold
                                if(validateData(stationData, currentStation, currentMeasurement, data, prev, 20)){
                                    //System.out.println("Temp within 20% offset, Valid!");
                                    stationData[currentStation][currentMeasurement][currentBacklog] = data;

                                }else{
                                    // Todo: Data invalid, correct the data with e

                                    stationData[currentStation][currentMeasurement][currentBacklog] = data;
                                }
                            }else{
                                // Todo: assign the data
                                //System.out.println(currentMeasurement + " - data: " + data);

                                stationData[currentStation][currentMeasurement][currentBacklog] = data;

                            }
                            // Add measurement data
                            measurementData.add(data);
                            // Increment current data
                            currentMeasurement++;
                        }
                    }
                    input = "";
                    timeout = 0;
                    
                }else{
                    timeout++;

                    if(timeout >= maxTimeout){
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


    /**
     * Validate data
     * Checks if new data is within offset range (in %)
     */
    private boolean validateData(String data[][][], int station, int measurement, String newValue, int prevValueIndex, int maxOffset){

        // Old value
        // Een meetwaarde voor de temperatuur wordt als irreëel beschouwd indien ze
        // 20% of meer groter is of kleiner is dan wat men kan verwachten op basis van
        // extrapolatie van de dertig voorafgaande temperatuurmetingen. In dat geval
        // wordt de geëxtrapoleerde waarde ± 20% voor de temperatuur opgeslagen.
        // Voor de andere meetwaarden wordt deze handelswijze niet toegepast.


        // Check if the old value was NULL or EMPTY
        if(data[station][measurement][prevValueIndex] == null || data[station][measurement][prevValueIndex].equals("")){
            // If true, the value = null or empty string!
            return true;
        }else{
            // Check if the new vaue is within the OFFSET threshold


            // Todo: validate data
            // Todo: Fix this data


            //New value
            float curValue = Float.parseFloat(newValue);

            if(curValue < 5 && curValue > -5){
                // Todo: handle other offset
               // System.out.println("Value:  " + curValue);
            }

            //Old value
            float oldValue = Float.parseFloat(data[station][measurement][prevValueIndex]);

            // offset (20%) from percent
            float percent = (oldValue * 0.01f); // 1% = / 100


           // System.out.println("Valid: " + newValue + "   Old value " +  oldValue  + "  Offset: " + maxOffset + "   maxDifference: " + percent);

            // If within threshold....
            // Todo: remove this after fixing calculation!
            if(true){
                return true;
            }

        }

        return false;
    }

    /**
     * Extrapolates missing data
     * @param data List of all data
     * @param station current station index
     * @param measurement current measurement index
     * @param dataIndex current data index
     */
    private String CorrectMissingData(String data[][][], int station, int measurement, int dataIndex){

        if(station >= 0){
           // System.out.println("This!");
            return  "0";
        }

        //String value = data[station][measurement][dataIndex] = input;

        // Loop through the backlog:
        for(int cb = 0; cb < data[station][measurement][dataIndex].length(); cb++){
            // Get all measurements

            if(data[station][measurement][dataIndex].equals("")){
                // No data
            }else{
                // Calculate...
            }
        }

        for (int sd = 0; sd < data.length; sd++){
            System.out.println();
            System.out.println();
            System.out.print(sd);
            System.out.print(": ");

            for(int md = 0; md < data[sd].length; md++){
                System.out.print("..");
                System.out.print(md);

            }
        }

        return "";
    }


    private void HandleData(String[] data){

        for (int i = 1; i < data.length; i++){
            System.out.println("ID " + data[i]);

            if(data[i].equals("")){
                for (int x = 0; x < 1000; x++){
                    System.out.println("MISSING VARIABLE ");
                }
            }
        }

    }
}
