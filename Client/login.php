<?php
// Made by Jarco - © 2019

// Load settings!
require 'include/layout/headersettings.php';

// Create settings
$headerSettings = new HeaderSettings();
$headerSettings->AddStyle("style/main.css");
$headerSettings->redirect = false;
$headerSettings->title = 'Login';

// Include header
require 'include/layout/header.php';

$onlyIcon = true;

require 'include/layout/navbar.php';
?>

<?php require "include/functions.php"?>



<!-- Content  -->
<div class="maindiv">
    <div class="login-div">

      <form action = "login.php" class="login-form" method = "POST" >
        <h1>Login</h1>

        <label for="username" class="tl">Username:</label>
        <input type="text" placeholder="Username" name="username" required>

        <label for="password" class="tl">Password:</label>
        <input type="password" placeholder="Password" name="password" required>

        <button type="submit">Login</button>
      </form>


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
            $_SESSION['apikey'] = $result->data["apikey"];

            header("Location: index.php");  //redirect the user to the webpage
          }else{
            // TODO:
            echo '<p class="error">' . $result->message . '</p>';
          }
        }
       ?>


  </div>
</div>

<?php include 'include/layout/footer.php';  ?>
