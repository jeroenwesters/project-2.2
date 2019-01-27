
public class Timer {

    private long startTime = 0;
    private long endTime = 0;
    private String description = "";

    public void Start(String description){
        startTime = System.nanoTime();
        this.description = description;
        System.out.println(description + " - start");

    }

    public void Stop(){
        endTime = (System.nanoTime() - startTime);

        double sec = (double)endTime / 1000000000;

        System.out.println(String.format("%s duration: %f seconds", description, sec));
    }
}
