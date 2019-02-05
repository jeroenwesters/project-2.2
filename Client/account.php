<?php
// Made by Jarco & Jeroen - © 2019

// Load settings!
require 'include/layout/headersettings.php';
require 'include/functions.php';

// Create settings
$headerSettings = new HeaderSettings();
$headerSettings->AddStyle("style/main.css");
$headerSettings->title = 'Home';

require 'include/layout/header.php';
require 'include/layout/navbar.php';
?>

<!-- Content  -->
<div class="maindiv">
  <h1>Account</h1>
</div>
<div class="center-box admin-register">

<?php
$userid = $_SESSION['currentuserid'];
$result = getAccountDetails($userid);
$username = $result->data[0]["username"];
echo "Change your password ". $username."<br>";
echo "<br>";
 ?>

 <form class="login-form" action = "account.php" method ="POST">
   <label for="password0" class="tl">Enter current password:</label>
   <input type="password" name="password0" placeholder="old password" size ="40" required>

   <label for="password1" class="tl">Enter new password</label>
   <input type = "password"  name="password1" placeholder="new password" size ="40" maxlength="100" required>

   <label for="password2" class="tl">Repeat new password</label>
   <input type = "password"  name="password2" placeholder="repeat password" size ="40" maxlength="100" required>

   <button class='ripple' type="submit" value="Submit">Submit</button>
   <br>
 </form>
</div>

<?php

if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)){
  $oldPass = $_POST['password0'];
  $newPass = $_POST['password1'];
  $repeatNewPass = $_POST['password2'];

  $result = changePassword($userid, $oldPass, $newPass, $repeatNewPass);
  echo "<div class='center-box admin-register'>";

  // If failed
  if($result->error){
    echo '<h1>Failed!</h1><br>';
    echo  $result->message;
  }
  // if Succeeded
  else{
    echo '<h1>Succeed!</h1><br>';
    echo  $result->message;
  }
  echo '</div></div>';

}

include 'include/layout/footer.php';  ?>
