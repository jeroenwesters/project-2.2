package model;

import java.lang.reflect.Field;
import java.sql.Date;
import java.sql.Time;
import java.util.List;

public class Measurement {

    private int stationNumber;
    private Date date;
    private Time time;
    private float temperature;
    private float dewPoint;
    private float stp;
    private float slp;
    private float visibility;
    private float windSpeed;
    private float precipitate;
    private float snow;
    private int frshtt;
    private float cloudsPercentage;
    private int windDirection;

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

    public int getStationNumber() {
        return stationNumber;
    }

    public void setStationNumber(int stationNumber) {
        this.stationNumber = stationNumber;
    }

    public Date getDate() {
        return date;
    }

    public void setDate(Date date) {
        this.date = date;
    }

    public Time getTime() {
        return time;
    }

    public void setTime(Time time) {
        this.time = time;
    }

    public float getTemperature() {
        return temperature;
    }

    public void setTemperature(float temperature) {
        this.temperature = temperature;
    }

    public float getDewPoint() {
        return dewPoint;
    }

    public void setDewPoint(float dewPoint) {
        this.dewPoint = dewPoint;
    }

    public float getStp() {
        return stp;
    }

    public void setStp(float stp) {
        this.stp = stp;
    }

    public float getSlp() {
        return slp;
    }

    public void setSlp(float slp) {
        this.slp = slp;
    }

    public float getVisibility() {
        return visibility;
    }

    public void setVisibility(float visibility) {
        this.visibility = visibility;
    }

    public float getWindSpeed() {
        return windSpeed;
    }

    public void setWindSpeed(float windSpeed) {
        this.windSpeed = windSpeed;
    }

    public float getPrecipitate() {
        return precipitate;
    }

    public void setPrecipitate(float precipitate) {
        this.precipitate = precipitate;
    }

    public float getSnow() {
        return snow;
    }

    public void setSnow(float snow) {
        this.snow = snow;
    }

    public int getFrshtt() {
        return frshtt;
    }

    public void setFrshtt(int frshtt) {
        this.frshtt = frshtt;
    }

    public float getCloudsPercentage() {
        return cloudsPercentage;
    }

    public void setCloudsPercentage(float cloudsPercentage) {
        this.cloudsPercentage = cloudsPercentage;
    }

    public int getWindDirection() {
        return windDirection;
    }

    public void setWindDirection(int windDirection) {
        this.windDirection = windDirection;
    }

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

    public void print() {
        System.out.println(this.toString());
    }
}
