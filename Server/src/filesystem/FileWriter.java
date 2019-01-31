package filesystem;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.nio.ByteBuffer;
import java.util.ArrayList;

/**
 * @author      Emiel van Essen <emiel@teaspoongames.com>
 * @version     1.4
 * @since       1.0
 */
public class FileWriter {

    /**
     * Amount of measurements/stations per measurement file.
     */
    private static int currentMeasurementCollection = -1;

    private static ArrayList<float[]> dataBuffer = new ArrayList<>();

    /**
     * Adds measurement to file system.
     * <p>
     * Adds measurement to file, but firstly it will convert the measurement to a byte array consistent of 32bit floats.
     *
     * @param measurement The measurement to be added.
     */
    private static synchronized void addMeasurement(float[] measurement) {
        if(currentMeasurementCollection != (int)measurement[6]) {
            // Start thread to proccess the buffer!


            FilePusher fp = new FilePusher((ArrayList)dataBuffer.clone(), new int[]{(int)measurement[1], (int)measurement[2],
                    (int)measurement[3], (int)measurement[4], (int)measurement[5], (int)measurement[6]});

            // Start thread
            fp.start();


            // Reset buffer, and add
            dataBuffer.clear();
            dataBuffer.add(measurement);
            currentMeasurementCollection = (int)measurement[6];
        }
        else {
            // Add to the buffer!
            dataBuffer.add(measurement);
        }
    }

    public static synchronized void addMeasurements(float[][] measurements) {
        // Add new data to the collection

        for (float[] measurement : measurements) {
            addMeasurement(measurement);
        }

    }

    // Processes data further
    private static void ProcessData(float[][] measurements){
        for (float[] measurement : measurements) {
            addMeasurement(measurement);
        }
    }




}

class FilePusher implements Runnable {

    private ByteArrayOutputStream stream;
    private ArrayList<float[]> dataBuffer;

    private Thread t;
    private int datetime[];

    FilePusher(ArrayList<float[]> dataBuffer, int datetime[]) {
        this.dataBuffer = dataBuffer;
        this.datetime = datetime;
        stream = new ByteArrayOutputStream();

    }

    @Override
    public void run() {
        // Process data to bytes!
        for (float[] measurement : dataBuffer) {
            addToByteArray(measurement);
        }

        try {
            String filePath = "Measurements/" + (int)datetime[0] + "/" + (int)datetime[1] +  "/" + (int)datetime[2] + "/" + (int)datetime[3] + "/" + (int)datetime[4] + "/";
            File measurementFile = new File(filePath + "measurement_" +  (int)datetime[5] + ".bin");
            measurementFile.getParentFile().mkdirs();

            FileOutputStream fos = new FileOutputStream(measurementFile, true);
            fos.write(stream.toByteArray());
            fos.flush();
            fos.close();
            //System.out.println("File writen!");
            stream = null;

        }
        catch (IOException e) {
            e.printStackTrace();
        }
    }

    public void start () {
        if (t == null) {
            t = new Thread (this);
            t.start ();
        }
    }

    private void addToByteArray(float[] measurement) {
        if(stream == null) {
            stream = new ByteArrayOutputStream();
        }
        try {
            for (int i = 0; i < measurement.length; i++) {
                stream.write(float2ByteArray(measurement[i]));
            }
        }
        catch (IOException e) {
            e.printStackTrace();
        }
    }

    private static byte [] float2ByteArray (float value)
    {
        return ByteBuffer.allocate(4).putFloat(value).array();
    }
}

