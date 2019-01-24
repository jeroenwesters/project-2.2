package filesystem;

import model.Measurement;

import java.io.ByteArrayOutputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.nio.ByteBuffer;
import java.util.Arrays;
import java.util.List;

public class FileWriter {

    List<String> binaryMeasurements;

    public FileWriter() {

    }

    public void addMeasurement(Measurement measurement) {
        Thread writerThread = new Thread(new ConvertMeasurement(measurement));
        writerThread.run();
    }
}

class ConvertMeasurement implements Runnable {

    private Measurement measurement;
    private byte[] array;

    public ConvertMeasurement(Measurement measurement) {
        this.measurement = measurement;
    }

    public void run() {
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
            FileOutputStream fos = new FileOutputStream("measurement" + measurement.getDate().toString() + ".bin", false);
            fos.write(array);
            fos.close();
        }
        catch (IOException e) {
            e.printStackTrace();
        }
    }

    public static byte [] float2ByteArray (float value)
    {
        return ByteBuffer.allocate(4).putFloat(value).array();
    }
}
