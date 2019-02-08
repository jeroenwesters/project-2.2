import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;


/**
 * @author      Jeroen Westers
 * @version     1
 */
public class Server {

    // Server settings
    private int socket_port =    7789;
    private int maxAllowedClients = 0;

    // Keeping track of the clients
    private static int clientCount = 0;

    /**
     * Constructor to create the server class
     * @param maxAllowedClients Maxium clients allowed on the server
     */
    public Server(int maxAllowedClients){
        this.maxAllowedClients = maxAllowedClients;
    }


    /**
     * Add a client to the counter!
     */
    synchronized static void AddClient(){
        clientCount++;
    }

    /**
     * Remove a client from the counter!
     */
    synchronized static void RemoveClient(){
        clientCount--;
        System.out.println("Client count is now: " + clientCount);
    }

    /**
     * Keeps listening for new connections
     */
    public void run() throws IOException {

        System.out.println(String.format("Server running on port: %d, with max: %d clients", socket_port, maxAllowedClients));


        // Create listener on port X
        ServerSocket listener = new ServerSocket(socket_port);

        // Pool with 800 threads for the sockets
        ExecutorService executorService = Executors.newFixedThreadPool(800);

        try {
            // Keep searching!
            while (true) {
                try {
                    final Socket connection = listener.accept();
                    //System.err.println("SERVER - connection from: " + connection.getInetAddress());
                    executorService.execute(new ServerTask(connection));


                    AddClient();
                    // Debug line, to see how many clients there are
//                    if(clientCount % 100 == 0){
//                        System.out.println(String.format("Client count: >= %d", clientCount));
//                    }



                } catch (IOException e) {
                    throw new RuntimeException(e);
                }


            }
        }
        finally {
            listener.close();
        }

    }

}
