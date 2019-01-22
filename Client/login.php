<?php
require 'header.php';

?>
<html>
<body>
  <form action = "login.php" method = "POST">
      <table>
        <tr>
          <td>Gebruikersnaam:</td>
          <td><input type ="text" name="username"/></td>
        </tr>
        <tr>
          <td>Wachtwoord:</td>
          <td><input type = "password" name="password"/></td>
        </tr>
        <tr>
          <td><input type = "reset" name = "reset" value = "Leegmaken"></td>
          <td><input type = "submit" value = "Stuur"></td>
        </tr>
      </table>
    </form>

<?php
  $user1 = array("user1", "password1");
  $user1[0] = "user";
  $user1[1] = "password";
  if(isset($_POST['password']))
  {
    $username = $_POST['username'];//convert filled in username to a string
  	$password = $_POST['password'];//convert filled in password to a string

    if($username == $user1[0])
    {
      if($password == $user1[1])
      {
        header("Location: webpage.php");
      }
      else {
        echo "<br>Wachtwoord incorrect<br>";
      }
    }
    else{
      echo "<br>Gebruikersnaam incorrect<br>";
    }
  }
  require 'footer.php';

 ?>
