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
    // $query = "SELECT * FROM users WHERE `userid` = '$userid'";
    // $result = $conn->query($query);
    // $row = $result-> fetch_assoc();
    $result = getAccountDetails($userid);

foreach ($result->data as $row) {
?>
<div class="maindiv">
  <h1>Edit account</h1>
<div class="center-box">
<br>
<form class="center-item" action = "edit.php" method ="POST">
    <table>
      <tr>
        <td>Username:</td>
        <td><input type ="text" name="username" size ="20" maxlength="50" required value="<?php echo $row["username"];?>"/></td>
      </tr>
      <tr>
        <td></td>
        <td><label for="password0">Reset to standard password?</label>
          <input type = "checkbox" id = "password0" name="password0"></td>
      </tr>
      <tr>
        <td></td>
        <td><label for="admin">Admin rights?</label>
            <input type = "checkbox" id="admin" name = "admin" value = "yes" <?php if($row["admin"] == "1"){echo "checked";}?>></td>
      </tr>
      <tr>
        <td></td>
        <td>
          <button type="button" onclick='location.href="admin.php";'>Cancel </button>
          <input type = "submit" value = "Edit">
        </td>
      </tr>
    </table>
</form>
</div>
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

    // If failed
    if($result->error){
      echo $result->message . "   ";
      echo "<button onclick='history.go(-2);'>Go back</button>";
      echo "<button onclick='history.go(-1);'>Try again</button>";
    }else{
      echo $result->message . "   ";
      echo "<button onclick='history.go(-2);'>Back </button>";
    }
      //header("Location: admin.php");

}
 ?>
