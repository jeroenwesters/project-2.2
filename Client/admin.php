<?php
// Made by Jeroen & Emiel - © 2019

// Load settings!
require 'include/layout/headersettings.php';
require 'include/functions.php';

// Create settings
$headerSettings = new HeaderSettings();
$headerSettings->AddStyle("style/main.css");
$headerSettings->title = 'Home';
$headerSettings->requireAdmin = true;

require 'include/layout/header.php';
require 'include/layout/navbar.php';
?>

<!-- Content  -->
<div class="maindiv">
  <h1>Admin panel</h1>
<div class="center-box">

<p>Create a new account:</p>
<br>

<form class="center-item" action = "register.php" method ="POST">
    <table>
      <tr>
        <td>Username:</td>
        <td><input type ="text" name="username" size ="20" maxlength="50" required/></td>
      </tr>
      <tr>
        <td>Password:</td>
        <td><input type = "password" name="password0" size ="20" maxlength="100" required/></td>
      </tr>
      <tr>
        <td>Repeat password:</td>
        <td><input type = "password" name="password1" size ="20" maxlength="100" required/></td>
      </tr>
      <tr>
        <td></td>
        <td><label for="admin">Admin rights?</label><input type = "checkbox" id="admin" name = "admin" value = "yes"></td>
      </tr>
      <tr>
        <td></td>
        <td><input type = "submit" value = "register"></td>
      </tr>
    </table>
</form>
</div>


<br>
<br>
<br>
  <table id="topten" class="center-item" width="80%" border="1">
    <thead>
      <tr>
        <th><strong>ID</strong></th>
        <th><strong>UserID</strong></th>
        <th><strong>Username</strong></th>
        <th><strong>Admin</strong></th>
        <th><strong>Api Key</strong></th>
        <th><strong>Edit</strong></th>
        <th><strong>Delete</strong></th>
      </tr>
    </thead>
    <tbody>
<?php
      if(isset($_POST['result'])){
        var_dump($_POST['result']);
      }

      $result = getAccounts();
      // Check for error
      if($result->error == false){
        // below line for data debug
        $count=0;
        foreach ($result->data as $row){
          $count += 1;
          echo "<tr>";
            echo"<td align='center'>".$count."</td>";
            echo"<td align='center'>".$row["userid"]."</td>";
            echo"<td align='center'>".$row["username"]."</td>";
            echo"<td align='center'>".$row["admin"]."</td>";
            echo"<td align='center'>".$row["api_key"]."</td>";
            echo"<td align='center'>
                  <a href='edit.php?id=".$row["userid"]."'>Edit</a>
                  </td>";
            echo"<td align='center'>
                  <a href='delete.php?id=".$row["userid"]."'>Delete</a>
                  </td>";
          echo "</tr>";
      }
      }else{
        // echo the error!
        echo $result->message;
      }

      echo "</tbody>";
      echo "</table>";

?>



  </div>
</div>

<?php include 'include/layout/footer.php';  ?>
