package filesystem;

import model.Measurement;

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
    private int heapSize;

    /**
     * Current measurement amount in file.
     */
    private int amount = 0;

    /**
     * Current heap being wrote.
     */
    private int heapId = 0;

    /**
     * Current timestamp
     */
    private int currentTime = -1;

    /**
     * Constructor.
     * @param heapSize The selected heap size.
     */
    public FileWriter(int heapSize) {
        this.heapSize = heapSize;
    }

    /**
     * Adds measurement to file system.
     * <p>
     * Adds measurement to file, but firstly it will convert the measurement to a byte array consistent of 32bit floats.
     *
     * @param measurement The measurement to be added.
     */
    public void addMeasurement(Measurement measurement) {
        byte[] data = convertToByteArray(measurement);
        try {
            if(heapSize < 0) {
                heapId = 0;
            }
            else {
                if (measurement.getTime().getSeconds() != currentTime) {
                    currentTime = measurement.getTime().getSeconds();
                    amount = 0;
                    heapId = 0;
                }
                amount++;
                if (amount > heapSize) {
                    amount = 1;
                    heapId++;
                }
            }
            //String filePath = "Measurements/" + (measurement.getDate().getYear() + 1900) + "/" + (measurement.getDate().getMonth() + 1) +  "/" + measurement.getDate().getDate() + "/" + measurement.getTime().getHours() + "/" + measurement.getTime().getMinutes() + "/" + measurement.getTime().getSeconds() + "/";
            String filePath = "/mnt/private/Measurements/" + (measurement.getDate().getYear() + 1900) + "/" + (measurement.getDate().getMonth() + 1) +  "/" + measurement.getDate().getDate() + "/" + measurement.getTime().getHours() + "/" + measurement.getTime().getMinutes() + "/" + measurement.getTime().getSeconds() + "/";
            File measurementFile = new File(filePath + "measurementheap_" + heapId + ".bin");
            measurementFile.getParentFile().mkdirs();
            FileOutputStream fos = new FileOutputStream(measurementFile, true);
            fos.write(data);
            fos.close();
            fos.flush();
        }
        catch (IOException e) {
            e.printStackTrace();
        }
    }

    private byte[] convertToByteArray(Measurement measurement) {
        ByteArrayOutputStream outputStream = new ByteArrayOutputStream();
        try {
            outputStream.write(float2ByteArray(measurement.getStationNumber()));
            outputStream.write(float2ByteArray(measurement.getDate().getYear() + 1900));
            outputStream.write(float2ByteArray(measurement.getDate().getMonth() + 1));
            outputStream.write(float2ByteArray(measurement.getDate().getDate()));
            outputStream.write(float2ByteArray(measurement.getTime().getHours()));
            outputStream.write(float2ByteArray(measurement.getTime().getMinutes()));
            outputStream.write(float2ByteArray(measurement.getTime().getSeconds()));
            outputStream.write(float2ByteArray(measurement.getTemperature()));
            outputStream.write(float2ByteArray(measurement.getDewPoint()));
            outputStream.write(float2ByteArray(measurement.getStp()));
            outputStream.write(float2ByteArray(measurement.getSlp()));
            outputStream.write(float2ByteArray(measurement.getVisibility()));
            outputStream.write(float2ByteArray(measurement.getWindSpeed()));
            outputStream.write(float2ByteArray(measurement.getPrecipitate()));
            outputStream.write(float2ByteArray(measurement.getSnow()));
            outputStream.write(float2ByteArray(measurement.getFrshtt()));
            outputStream.write(float2ByteArray(measurement.getCloudsPercentage()));
            outputStream.write(float2ByteArray(measurement.getWindDirection()));

            return outputStream.toByteArray();
        }
        catch (IOException e) {
            e.printStackTrace();
        }
        return null;
    }

    private static byte [] float2ByteArray (float value)
    {
        return ByteBuffer.allocate(4).putFloat(value).array();
    }
}
