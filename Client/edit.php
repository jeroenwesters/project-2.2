<?php
// Made by Jarco, Emiel - © 2019

// Load settings!
require 'include/layout/headersettings.php';
require 'include/functions.php';

// Create settings
$headerSettings = new HeaderSettings();
$headerSettings->AddStyle("style/main.css");
$headerSettings->title = 'Edit';
$headerSettings->requireAdmin = true;

require 'include/layout/header.php';
require 'include/layout/navbar.php';


if(isset($_GET['id'])){
  $_SESSION['userid'] = $_GET['id'];
  $userid = $_SESSION['userid'];
  // $query = "SELECT * FROM users WHERE `userid` = '$userid'";
  // $result = $conn->query($query);
  // $row = $result-> fetch_assoc();
  $result = getAccountDetails($userid);

  foreach ($result->data as $row) {
?>

 <!-- Content  -->
<div class="maindiv">
<div class="center-box">
<br>
<h1>Edit user</h1>

<form class="login-form" action = "edit.php" method ="POST">
  <label for="username" class="tl">Username:</label>
  <input type="text" placeholder="Username" name="username" size ="20" maxlength="50" value="<?php echo $row["username"];?>" required>

  <label for="password0">Reset to standard password?</label>
  <input type = "checkbox" id = "password0" name="password0">

  <label for="admin">Admin rights?</label>
  <input type = "checkbox" id="admin" name = "admin" value = "yes" <?php if($row["admin"] == "1"){echo "checked";}?>></td>

  <button class='ripple' type="submit" value="Submit">Submit</button>
  <br>
  <button class='ripple' type="button" onclick='location.href="admin.php";'>Cancel</button>
</form>

<?php
}
}
  if($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)){
    $username = $_POST['username'];
    if(isset($_POST['admin'])){
      $admin = 1;
    }
    else{
      $admin = 0;
    }
    if(isset($_POST['password0'])){
      $password0 = 1;
    }
    else{
      $password0 = 0;
    }
    $userid = $_SESSION['userid'];

    $result = updateAccount($userid, $username, $password0, $admin);

    echo '<div class="maindiv"><div class="center-box">';
    // If failed
    if($result->error){
      echo '<h1>Failed!</h1><br><br>';
      echo  '<p>' . $result->message . "</p>";
      echo "<button class='ripple' onclick='history.go(-2);'>Go back</button>";
      echo "<button class='ripple' onclick='history.go(-1);'>Try again</button>";
    }else{
      echo '<h1>Succeed!</h1><br><br>';
      echo  '<p>' . $result->message . "</p>";
      echo "<button class='ripple' onclick='history.go(-2);'>Back </button>";
    }
    echo '</div></div>';
}
 ?>
</div>


</div>

<?php include 'include/layout/footer.php';  ?>
