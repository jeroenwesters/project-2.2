/**
 * @author      Emiel van Essen <emiel@teaspoongames.com>
 * @version     1.1
 * @since       1.0
 */
public class main {

    /**
     * Main function
     */
    public static void main(String[] args) throws Exception {
        // Creates the server
        Server server = new Server(800);
        // Run server (Could make a thread of it)
        server.run();
    }
}