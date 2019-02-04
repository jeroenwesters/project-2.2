<?php
  require 'include/layout/headersettings.php';
  require 'include/functions.php';

  // Create settings
  $headerSettings = new HeaderSettings();
  $headerSettings->AddStyle("style/main.css");
  $headerSettings->title = 'Home';
  $headerSettings->requireAdmin = true;

  require 'include/layout/header.php';
  require 'include/layout/navbar.php';

  if(isset($_GET['id'])){
    $_SESSION['userid'] = $_GET['id'];
    $userid = $_SESSION['userid'];

    // $query = "SELECT `username` FROM users WHERE `userid` = '$userid'";
    // $result = getData($conn,$query); //put the result of the query in a
    // $username = $result[0]["username"];

    $result = getAccountDetails($userid);

    $username = $result->data[0]['username'];
    // $username = $result[0]['username'];
}
?>
<div class="maindiv">
  <h1>Admin panel</h1>
<div class="center-box">

  <?php echo "Are you sure you want to delete $username?";?>
  <br>
  <br>

  <form class="center-item" action="delete_user.php">
  <button type="submit">Yes</button>
  <button type="button" onclick='location.href="admin.php";'>Cancel </button>
  </form>
</div>
