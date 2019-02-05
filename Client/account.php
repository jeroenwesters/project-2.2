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

<?php
$userid = $_SESSION['currentuserid'];
$result = getAccountDetails($userid);
$username = $result->data[0]["username"];
echo "Change your password ". $username;
 ?>

 <form class="login-form" action = "change_password.php" method ="POST">
   <label for="password0" class="tl">Enter current password:</label>
   <input type="password" name="password0" required>

   <label for="password1">Enter new password</label>
   <input type = "password"  name="password1" required>

   <label for="password2">Repeat new password</label>
   <input type = "password"  name="password2" required>

   <button class='ripple' type="submit" value="Submit">Submit</button>
   <br>
   <button class='ripple' type="button" onclick='location.href="admin.php";'>Cancel</button>
 </form>

</div>

<?php include 'include/layout/footer.php';  ?>
