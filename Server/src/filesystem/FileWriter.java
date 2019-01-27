package filesystem;

import model.Measurement;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.nio.ByteBuffer;

public class FileWriter {

    int heapSize;
    int amount;

    public FileWriter(int heapSize) {
        this.heapSize = heapSize;
    }

    public void addMeasurement(Measurement measurement) {
        ConvertMeasurement convertedMeasurement = new ConvertMeasurement(measurement, data -> {
            try {
                String filePath = "Measurements/" + (measurement.getDate().getYear() + 1900) + "/" + (measurement.getDate().getMonth() + 1) +  "/" + measurement.getDate().getDate() + "/" + measurement.getTime().getHours() + "/" + measurement.getTime().getMinutes() + "/" + measurement.getTime().getSeconds() + "/";
                File measurementFile = new File(filePath + "measurement.bin");
                measurementFile.getParentFile().mkdirs();
                measurementFile.createNewFile();
                FileOutputStream fos = null;
                fos = new FileOutputStream(measurementFile, true);
                fos.write(data);
                fos.close();
                fos.flush();
            }
            catch (IOException e) {
                e.printStackTrace();
            }
        });
        Thread writerThread = new Thread(convertedMeasurement);
        writerThread.run();
    }
}

class ConvertMeasurement implements Runnable {

    private Measurement measurement;
    private byte[] array;
    private ConvertedListener listener;

    public ConvertMeasurement(Measurement measurement, ConvertedListener listener) {
        this.measurement = measurement;
        this.listener = listener;
    }

    public synchronized void run() {
        ByteArrayOutputStream outputStream = new ByteArrayOutputStream( );
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

            array = outputStream.toByteArray();
            listener.onConverted(array);

        }
        catch (IOException e) {
            e.printStackTrace();
        }
    }

    private static byte [] float2ByteArray (float value)
    {
        return ByteBuffer.allocate(4).putFloat(value).array();
    }

    public byte[] getData() {
        return array;
    }
}
