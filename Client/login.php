<?php require "include/functions.php"?>

<link rel="stylesheet" type="text/css" href="style/style.css">
<div class="center">
<html>
<body>
  <form action = "login.php" method = "POST" >
      <table border="0" align="center">
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
  if(isset($_POST['password']))
  {
    $username = $_POST['username']; //make a string of the username
  	$password = $_POST['password']; //make a string of the password


  	$result = userlogin($username, $password); //put the result of the query in a variable

    if($result->error == false){
      //Set the session variables
      session_start();
      $_SESSION['loggedIn'] = true;
      $_SESSION['currentuser'] = $result->data['username'];
      $_SESSION['admin'] = $result->data["admin"];

      header("Location: webpage.php");  //redirect the user to the webpage
    }else{
      // TODO:
      echo $result->message;
    }
  }
 ?>
</div>
