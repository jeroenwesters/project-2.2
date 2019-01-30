<?php require "include/config.php"?>
<link rel="stylesheet" type="text/css" href="style/style.css">
</div>
<div class="center">
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
          <td></td>
          <td><input type = "submit" value = "Login"></td>
        </tr>
      </table>
  </form>
</body>
</html>
<?php
  session_start();
  if(isset($_POST['password']))
  {
    $username = escapeTrimString($conn, $_POST['username']); //make a string of the username
  	$password = $_POST['password']; //make a string of the password

    $query = "SELECT username, password, admin
  				    FROM users
  				    WHERE username = '$username'"; //put the sql query in a string

  	$result = getData($conn,$query); //put the result of the query in a variable

    if (count($result) > 0) //Check if the username is stored in the database
    {
      $hash = $result[0]["password"]; //puts the password in a hash
      if(password_verify($password, $hash) == true) //if the password is correct
      {
        //Set the session variables
        $_SESSION['loggedIn'] = true;
        $_SESSION['currentuser'] = $result[0]["username"];
        $_SESSION['admin'] = $result[0]["admin"];

        header("Location: webpage.php");  //redirect the user to the webpage
      }
      else {
      echo "<script>alert('Wachtwoord incorrect')</script>";
      }
    }
    else{
      echo "<br>Gebruikersnaam incorrect<br>";
    }
  }
  require 'include/footer.php';
 ?>
</div>
<div class="body">
