package model;

import java.lang.reflect.Field;
import java.sql.Date;
import java.sql.Time;
import java.util.List;

/**
 * @author      Emiel van Essen <emiel@teaspoongames.com>
 * @version     1.1
 * @since       1.0
 */
public class Measurement {

    /**
     * Station ID.
     */
    private int stationNumber;

    /**
     * Date of the measurement.
     */
    private Date date;

    /**
     * Time of the measurement.
     */
    private Time time;

    /**
     * Temperature in degree Celsius.
     */
    private float temperature;

    /**
     * Dew point in degree Celsius.
     */
    private float dewPoint;

    /**
     * Air pressure on station level.
     */
    private float stp;

    /**
     * Air pressure on sea level.
     */
    private float slp;

    /**
     * Visibility in kilometers.
     */
    private float visibility;

    /**
     * Wind speed in Km/H.
     */
    private float windSpeed;

    /**
     * Precipitate in centimeters.
     */
    private float precipitate;

    /**
     * Fallen snow in centimeters.
     */
    private float snow;

    /**
     * Binary representation of Freezing / Rain / Snow / Hail / Thunder / Tornado
     */
    private int frshtt;

    /**
     * Cloudiness in percentages.
     */
    private float cloudsPercentage;

    /**
     * Wind direction in degrees.
     */
    private int windDirection;

    /**
     * Constructor.
     * <p>
     * Sets all the values of the class.
     */
    public Measurement(int stationNumber, Date date, Time time, float temperature, float dewPoint, float stp,
                       float slp, float visibility, float windSpeed, float precipitate, float snow, int frshtt,
                       float cloudsPercentage, int windDirection) {
        this.stationNumber = stationNumber;
        this.date = date;
        this.time = time;
        this.temperature = temperature;
        this.dewPoint = dewPoint;
        this.stp = stp;
        this.slp = slp;
        this.visibility = visibility;
        this.windSpeed = windSpeed;
        this.precipitate = precipitate;
        this.snow = snow;
        this.frshtt = frshtt;
        this.cloudsPercentage = cloudsPercentage;
        this.windDirection = windDirection;
    }

    /**
     * Converts list of strings to all the variables of the measurement, effectively creating a measurements class
     *
     * @param data The measurement data in string array form.
     * @return the new and filled Measurement class.
     */
    public static Measurement fromData(List<String> data) {
        return new Measurement(Integer.parseInt(data.get(0)),
                Date.valueOf(data.get(1)), Time.valueOf(data.get(2)),
                Float.parseFloat(data.get(3)), Float.parseFloat(data.get(4)),
                Float.parseFloat(data.get(5)), Float.parseFloat(data.get(6)),
                Float.parseFloat(data.get(7)), Float.parseFloat(data.get(8)),
                Float.parseFloat(data.get(9)), Float.parseFloat(data.get(10)),
                Integer.parseInt(data.get(11)), Float.parseFloat(data.get(12)),
                Integer.parseInt(data.get(13)));
    }

    /**
     * @return Station number.
     */
    public int getStationNumber() {
        return stationNumber;
    }

    /**
     * @return Date.
     */
    public Date getDate() {
        return date;
    }

    /**
     * @return Time.
     */
    public Time getTime() {
        return time;
    }

    /**
     * @return Temperature.
     */
    public float getTemperature() {
        return temperature;
    }

    /**
     * @return Dew point.
     */
    public float getDewPoint() {
        return dewPoint;
    }

    /**
     * @return Air pressure on station level.
     */
    public float getStp() {
        return stp;
    }

    /**
     * @return Air pressure on sea level.
     */
    public float getSlp() {
        return slp;
    }

    /**
     * @return Visibility.
     */
    public float getVisibility() {
        return visibility;
    }

    /**
     * @return Wind Speed.
     */
    public float getWindSpeed() {
        return windSpeed;
    }

    /**
     * @return Precipitate.
     */
    public float getPrecipitate() {
        return precipitate;
    }

    /**
     * @return Snow amount.
     */
    public float getSnow() {
        return snow;
    }

    /**
     * @return Freezing / Rain / Snow / Hail / Thunder / Tornado in binary.
     */
    public int getFrshtt() {
        return frshtt;
    }

    /**
     * @return Cloud percentage.
     */
    public float getCloudsPercentage() {
        return cloudsPercentage;
    }

    /**
     * @return Wind direction.
     */
    public int getWindDirection() {
        return windDirection;
    }

    /**
     * Converts measurement to string so it can be logged easily.
     *
     * @return multiline string with all variables.
     */
    public String toString() {
        StringBuilder result = new StringBuilder();
        String newLine = System.getProperty("line.separator");
        result.append("_________Measurement_________");
        result.append(newLine);
        Field[] fields = this.getClass().getDeclaredFields();
        for (Field field : fields) {
            result.append("  ");
            try {
                result.append(field.getName());
                result.append(": ");
                result.append(field.get(this));
            } catch (IllegalAccessException ex) {
                System.out.println(ex);
            }
            result.append(newLine);
        }
        result.append("_____________END_____________");

        return result.toString();
    }

    /**
     * Extrapolates temperature based on previous measurements.
     *
     * @param measurements array of previous measurements which contain a valid temperature.
     * @return the extrapolated temperature.
     */
    public static float calculateTemp(List<Measurement> measurements) {
        float temp = measurements.get(0).getTemperature();
        if(measurements.size() > 1) {
            float bT = measurements.get(0).getTemperature();
            float eT = measurements.get(measurements.size() - 1).getTemperature();

            float x = eT - bT;
            temp = (x / (measurements.size() - 1) + eT);
            System.out.println(String.format("begin: %.1f   -----   end: %.1f     extra: %.3f", bT, eT, temp));
        }
        else {
            System.out.println(String.format("begin: %.1f   -----   end: %.1f     extra: %.1f", temp, temp, temp));
        }
        return temp;
    }

    /**
     * Prints all values of class.
     */
    public void print() {
        System.out.println(this.toString());
    }
}
