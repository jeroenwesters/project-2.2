import database.Database;
import model.Measurement;

import javax.xml.crypto.Data;
import java.io.BufferedReader;
import java.io.Console;
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


    /**
     * Constructor
     * @param socket
     */
    public ServerTask(Socket socket) {
        parser = new XMLParser();
        this.socket = socket;

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


            Timer timer = new Timer();

            // Infinite loop to prevent thread from stopping
            while(true){

                // Read line (input)
                input = reader.readLine();

                // If there is no input, cancel
                if(input == null){
                    System.out.println("No input received, canceling");
                    break;
                }

                // Remove spaces from the input
                input = input.replaceAll("\\s","");


                // Check if the file starts or ends!
                if(input.equals("<WEATHERDATA>")){

                    // Set current station index to 0
                    currentStation = 0;

                    // Start debug timer
                    timer.Start("weatherdata");

                }else if (input.equals("</WEATHERDATA>")){
                    // End of file
                    timer.Stop();

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

                        // Todo: remove database stuff!
                        // Database.instance.insertMeasurement(Measurement.fromData(measurementData));


                    }else{
                        // Convert string (to get variable)
                        String data = parser.ParseData(input);

                        // Check for zero value
                        if(data.equals("")){
                            boolean usePrevious = false;

                            // No data, check if we can use previous data:
                            for(int d = 0; d < use_previous.length; d++){
                                if(currentMeasurement == use_previous[d]){
                                    // Todo:
                                    usePrevious = true;
                                    break;
                                }
                            }

                            // If these variables can't be calculated!
                            if(usePrevious){
                                // Get previous index!
                                int backlog = currentBacklog - 1;

                                if(backlog < 0){
                                    // Go back to the last index of the backlog!
                                    backlog = max_backlog -1;
                                }

                                System.out.println("using: " + backlog);
                                data = stationData[currentStation][currentMeasurement][backlog];
                                System.out.println("New data: " + input + "  " + data);




                            }else{
                                // Todo: EXTRAPOLATE!
                                System.out.println("EXTRAPOLATE data: " + input + "  -  " + data);


                                //data = CorrectMissingData(stationData, currentStation, currentMeasurement, currentBacklog);
                                // data = "My AVARAGE VALUE";
                                data = "0";
                            }

                        }


                        if(currentMeasurement == temp_id){
                            //System.out.println("Temperature data: " + data);

                            // Todo: Check for max 20% difference
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

                        //System.out.println(stationData[currentStation][currentMeasurement][currentBacklog]);
                        measurementData.add(data);

                        // Todo: Remove those lines, DEBUGGING PURPOSES
                        if(currentMeasurement == 3 && currentStation == 100){
                            //break;
                            //System.out.println(currentMeasurement + "Getting data from array: " + stationData[currentStation][currentMeasurement][currentMeasurement]);
                            //System.out.println(" ");
                            //System.out.print("Backlog Values: ");
                            // Get temperature!!
                            for(int cb = 0; cb < stationData[currentStation][temp_id].length; cb++){
                                //System.out.println(currentMeasurement + "Getting data from array: " + stationData[currentStation][currentMeasurement][cb]);


                                 System.out.print(stationData[currentStation][temp_id][cb] + ", " );
                            }

                        }



                        // Increment current data
                        currentMeasurement++;
                    }
                }

                input = "";
            }
        } catch (IOException e) {
            System.out.println("Error handling client ");
        } finally {
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

    /*
    Corrects missing data
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