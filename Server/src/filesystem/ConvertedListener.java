package filesystem;

/**
 * @author      Emiel van Essen <emiel@teaspoongames.com>
 * @version     1.0
 * @since       1.0
 */
public interface ConvertedListener {

    /**
    * Callback of conversion completion
    */
    void onConverted(byte[] data);
}
