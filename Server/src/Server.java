import filesystem.FileWriter;

import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;



// Server code itself (Setting up connections and threads)

public class Server {

    // Server settings
    private int socket_port =    7789;
    private int maxAllowedClients = 0;

    // Keeping track of the clients
    private static int clientCount = 0;


    public Server(int maxAllowedClients){
        this.maxAllowedClients = maxAllowedClients;
    }


    /**
     * not FINISHED
     * Add a client to the counter!
     */
    synchronized static void AddClient(){
        clientCount++;
    }

    /**
     * not FINISHED
     * Remove a client from the counter!
     */
    synchronized static void RemoveClient(){
        clientCount--;
        System.out.println("Client count is now: " + clientCount);
    }

    /**
     * Start thread
     */
    public void run(FileWriter writer) throws IOException {

        System.out.println(String.format("Server running on port: %d, with max: %d clients", socket_port, maxAllowedClients));


        // Create listener on port X
        ServerSocket listener = new ServerSocket(socket_port);


        try {
            // Keep searching!
            while (true) {

                // Check if we have reached the maximum amount of clients.
                if(clientCount < maxAllowedClients){
                    Socket socket = listener.accept();

                    AddClient();
                    // Debug line, to see how many clients there are
                    if(clientCount % 100 == 0){
                        System.out.println(String.format("Client count: >= %d", clientCount));
                    }

                    // Create thread with it's socket as parameter!
                    ServerTask s_task = new ServerTask(socket, writer);
                    // Run thread
                    s_task.start();

                }else{
                    // Debug the amount
                    // System.out.println("Client count is now: " + clientCount);
                }
            }
        }
        finally {
            listener.close();
        }

    }

}
