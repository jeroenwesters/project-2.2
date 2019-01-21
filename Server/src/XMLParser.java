import java.util.regex.Matcher;
import java.util.regex.Pattern;


public class XMLParser {




    //Pattern regex = Pattern.compile("(>)(.*)(<)", Pattern.MULTILINE);

    // Named pattern
    Pattern regex = Pattern.compile("(>)(?<value>.*)(<)", Pattern.MULTILINE);

    // Named pattern + group
    //Pattern regex = Pattern.compile("(<)(?<tag>.*)(>)(?<value>.*)(<)", Pattern.MULTILINE);

    public  XMLParser(){

    }

    public String ParseData(String input){

        //System.out.println("Input:" + input);
        //System.out.println("reg:" + regex);

        Matcher m = regex.matcher(input);

        if(m.find())
        {
            //System.out.println("FOUND MATCH:" + m.group(2));
            //return m.group(2); // Return value by index


            // Prepare result
            //result[0] = m.group("tag");     // Assign tag
            return m.group("value");   // Assign value


        }else{
            System.out.println("No MATCH FOUND, using fall back system:");

            // Split manual:
            String[] parts = input.split("[<>]");

            if(parts.length > 2){
                System.out.println(parts[1]);

                //result[0] = parts[1];     // Assign tag
                return parts[2];   // Assign value
            }
        }

        // Missing
        return "";
    }
}
