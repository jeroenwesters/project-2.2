import database.Database;
import model.Measurement;

import javax.xml.crypto.Data;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.Socket;
import java.util.ArrayList;
import java.util.List;



// Handles server functions
public class ServerTask extends Thread {

    // Generator settings (amount of stations and measurements)
    private final int amount_stations = 10;
    private  final int amount_measurements = 14;
    private  final int max_backlog = 30; // Amount of saved values

    private Socket socket = null;;
    private XMLParser parser = null;


    // Multidimensional array containing stations, measurements and data
    String stationData[][][] = new String[amount_stations][amount_measurements][max_backlog];
    int currentStation = 0;
    int currentMeasurement = 0;
    int currentBacklog = 0;


//    int values_avarage[] = {
//            3,  // temp
//    };

    private final int use_previous[] = {
            0,
            1,
            2,
            13,
    };

    private final int temp_id = 3;


    /**
     * Constructor
     * @param socket
     */
    public ServerTask(Socket socket) {
        parser = new XMLParser();
        this.socket = socket;

        //System.out.println(String.format("New thread started"));

    }


    /**
     * Start thread
     */
    public void run() {

        try {
            BufferedReader reader = new BufferedReader(new InputStreamReader(socket.getInputStream()));

            // Create data container
            List<String> measurementData = new ArrayList<>();

            boolean fileStart = false;
            boolean isReading = false;

            long startTime = 0;

            String input = "";






            // Infinite loop to prevent thread from stopping
            while(true){

                //System.out.println(stationData[0][0][0]);

                // Read line
                input = reader.readLine(); // remove spaces
                //System.out.println("CUR LINE: " + input);

                // No input
                if(input == null){
                    System.out.println("No input received, canceling");
                    break;
                }

                // Remove spaces
                input = input.replaceAll("\\s","");

                // Check if the file starts or ends!
                if(input.equals("<WEATHERDATA>")){
                    startTime = System.nanoTime();
                    fileStart = true;
                    currentStation = 0;


                }else if (input.equals("</WEATHERDATA>")){
                    // End of file
                    fileStart = false;
                    stopTimer("Measurement", startTime);
                    currentBacklog++;

                    if(currentBacklog >= max_backlog){
                        currentBacklog = 0;
                    }
                }else{

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
                        currentStation++;
                        currentMeasurement = 0;


                        // Done reading!
                        isReading = false;

                        //System.out.println("Done reading");

                        // Give data to !?


                        Database.instance.insertMeasurement(Measurement.fromData(measurementData));

                        //m.print();
                    }else{
                        // Convert string (to get variable)
                        //String[] data = parser.ParseData(input);
                        String data = parser.ParseData(input); // Change the 1 !!!
                        //System.out.println(data);


                        // Check for zero value
                        if(data.equals("")){
                            boolean usePrevious = false;

                            // No data, check if we can use previous data:
                            for(int d = 0; d < use_previous.length; d++){
                                if(currentMeasurement == use_previous[d]){
                                    // Todo:
                                    // use previous data
                                    usePrevious = true;
                                }
                            }

                            if(usePrevious){
                                // Todo: get old value
                                data = "My OLD VALUE";
                                data = CorrectMissingData(stationData, currentStation, currentMeasurement, currentBacklog);

                            }else{
                                // Todo: calculate avarage
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
                            System.out.println(" ");
                            System.out.print("Backlog Values: ");
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


    private void stopTimer(String desc, long startTime){
        long stopTime = (System.nanoTime() - startTime);

        double sec = (double)stopTime / 1000000000;

        System.out.println(String.format("%s timer stopped after: %f seconds", desc, sec));
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
            // If true, cant process this!
            return true;
        }else{
            // Check if the new vaue is within the OFFSET threshold


            // Todo: validate data
            // Todo: Fix this data


            //New value
            float curValue = Float.parseFloat(newValue);

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
            System.out.println("This!");
            return  "";
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
            System.out.print(sd);
            System.out.print(": ");

            for(int md = 0; md < data[sd].length; md++){
                System.out.print("..");
                System.out.print(md);

            }
        }

        return null;
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

            /*

            EXAMPLE DATA!


             */

            /* int station = "STN"; // station nummer
            date = "DATE"; // datum
            time = "TIME"; // tijd
            float temp = "TEMP"; // temperatuur
            float dewp = "DEWP"; // dauwpunt
            float stp = "STP"; // luchtdruk op stationniveau
            float slp = "SLP"; // luchtdruk op zee niveau
            float visib = "VISIB"; // Zichtbaarheid in kilometers
            float wdsp = "WDSP"; // windsnelheid in km/h
            float prcp = "PRCP"; // neerslag in CM
            float sndp = "SNDP"; // sneel in cm
            int frshtt = "FRSHTT"; // gebeurtinissen (binair weergegeven)
            float cldc = "CLDC"; // bewolking in procenten
            int wnddir = "WNDDIR"; // windrichting in graden 0 - 359 */

    }
}
