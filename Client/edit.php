<?php
  require 'include/header.php';


  if($_SESSION['admin'] != 1){ //check if the user is an admin
      // if he is not an admin, redirect him to the webpage
    header("Location: webpage.php");
  }
  if(isset($_GET['id'])){
    $_SESSION['userid'] = substr($_GET['id'],1,-1);
    $userid = $_SESSION['userid'];
    $query = "SELECT * FROM users WHERE `userid` = '$userid'";
    $result = $conn->query($query);
    $row = $result-> fetch_assoc();
}
?>

<form action = "edit.php" method ="POST">
    <table>
      <tr>
        <td>Username:</td>
        <td><input type ="text" name="username" size ="20" maxlength="50" required value="<?php echo $row["username"];?>"/></td>
      </tr>
      <tr>
        <td>Password:</td>
        <td><input type = "text" name="password0" size ="20" maxlength="100" required value="<?php echo $row["password"];?>"/></td>
      </tr>
      <tr>
        <td></td>
        <td><label for="admin">Admin rights?</label>
            <input type = "checkbox" id="admin" name = "admin" value = "yes" <?php if($row["admin"] == "1"){echo "checked";}?>></td>
      </tr>
      <tr>
        <td></td>
        <td><input type = "submit" value = "Edit"></td>
      </tr>
    </table>
</form>

<?php
  if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)){
    $username = $_POST['username'];
    $password0 = $_POST['password0'];
    if(isset($_POST['admin'])){
      $admin = 1;
    }
    else{
      $admin = 0;
    }
    $error = "";
    if(strlen($password0) < 5){
      $error = "The password should have at least 5 characters.";
    }
    if(strlen($error) > 0){
      echo $error;
    }
    else{
      $userid = $_SESSION['userid'];
      $query = "UPDATE `users` SET `username`= '$username', `password`='$password0', `admin`='$admin'
                WHERE `userid` = '$userid'";
      $result = doQuery($conn, $query);
      $sqlerror = $conn->error;
      if(strlen($sqlerror) < 1){
        echo "Update complete";
        header("Location: admin.php");
      }
      else if(strtok($sqlerror, " ") == "Duplicate"){
        echo "This username has already been taken";
      }
      else{
        echo "An sql error has occured:";
        echo $sqlerror;
      }
    }
  }
 ?>
