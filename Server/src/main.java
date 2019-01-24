import database.Database;
import filesystem.FileWriter;
import model.Measurement;

import java.util.ArrayList;
import java.util.List;

/**
 * Server program, for retrieving, converting and storing weather data
 */
public class main {

    /**
     * Main function
     */
    public static void main(String[] args) throws Exception {
        // Create server
//        Database db = new Database("root", "", "localhost", 3306, "unwdmi");
        List<String> data = new ArrayList<>();
        data.add("118550");
        data.add("2018-12-20");
        data.add("14:55:58");
        data.add("1.0");
        data.add("-0.8");
        data.add("1002.1");
        data.add("1019.3");
        data.add("21.0");
        data.add("4.2");
        data.add("0.17");
        data.add("1.1");
        data.add("111000");
        data.add("54.2");
        data.add("131");
        new FileWriter().addMeasurement(Measurement.fromData(data));
//        db.insertMeasurement(Measurement.fromData(data));
//        db.getPreviousMeasurement().print();


//        Server server = new Server(800); // 10 clients
//
//        // Run server (Could make a thread of it)
//        server.run();
    }
}