package filesystem;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.nio.ByteBuffer;

/**
 * @author      Emiel van Essen <emiel@teaspoongames.com>
 * @version     1.4
 * @since       1.0
 */
public class FileWriter {

    /**
     * Amount of measurements/stations per measurement file.
     */
    private static ByteArrayOutputStream stream;
    private static ByteArrayOutputStream oldStream = null;

    private static int currentMeasurementCollection = -1;

    /**
     * Adds measurement to file system.
     * <p>
     * Adds measurement to file, but firstly it will convert the measurement to a byte array consistent of 32bit floats.
     *
     * @param measurement The measurement to be added.
     */
    private static synchronized boolean addMeasurement(float[] measurement) {
        if(currentMeasurementCollection != (int)measurement[6]) {
            currentMeasurementCollection = (int)measurement[6];
            addToByteArray(measurement, true);
            return true;
        }
        else {
            addToByteArray(measurement, false);
        }

        return  false;
    }

    public static synchronized void addMeasurements(float[][] measurements) {

        for (float[] measurement : measurements) {
            addMeasurement(measurement);
        }

        if (oldStream != null) {
            FilePusher fp = new FilePusher(oldStream, measurements[0]);
            fp.start();
            oldStream = null;
        }
    }

    private static void addToByteArray(float[] measurement, boolean newCollection) {
        if(stream == null || newCollection) {
            oldStream = stream;
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

class FilePusher implements Runnable {

    private float[] measurement;
    private ByteArrayOutputStream stream;

    private Thread t;

    FilePusher(ByteArrayOutputStream stream, float[] measurement) {
        this.measurement = measurement;
        this.stream = stream;
    }

    @Override
    public void run() {
        try {
//          String filePath = "/mnt/private/Measurements/" + (int)measurement[1] + "/" + (int)measurement[2] +  "/" + (int)measurement[3] + "/" + (int)measurement[4] + "/" + (int)measurement[5] + "/";

            // Final use!
            //String filePath = "Measurements/" + (int)measurement[1] + "/" + (int)measurement[2] +  "/" + (int)measurement[3] + "/" + (int)measurement[4] + "/" + (int)measurement[5] + "/" + (int)measurement[6] + "/";
            //File measurementFile = new File(filePath + "measurements.bin");


            String filePath = "Measurements/" + (int)measurement[1] + "/" + (int)measurement[2] +  "/" + (int)measurement[3] + "/" + (int)measurement[4] + "/" + (int)measurement[5] + "/";
            File measurementFile = new File(filePath + "measurement_" +  (int)measurement[6] + ".bin");
            measurementFile.getParentFile().mkdirs();

            // TODO: reenable appending once issue is fixed to check if issue is really fixed.
            FileOutputStream fos = new FileOutputStream(measurementFile, true);
            fos.write(stream.toByteArray());
            fos.flush();
            fos.close();
            System.out.println("File writen!");

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
}

