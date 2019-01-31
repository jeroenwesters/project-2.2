<?php
    require 'include/header.php';

    echo "you are in an admin panel right now";
    if($_SESSION['admin'] != 1){ //check if the user is an admin
      // if he is not an admin, redirect him to the webpage
      header("Location: webpage.php");
    }
?>

<br><br>
Create a new account:
<br>
<form action = "register.php" method ="POST">
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
<br>
<br>
<br>
  <table width="80%" border="1">
    <thead>
      <tr>
        <th><strong>Number</strong></th>
        <th><strong>UserID</strong></th>
        <th><strong>Username</strong></th>
        <th><strong>password</strong></th>
        <th><strong>Admin</strong></th>
        <th><strong>Edit</strong></th>
        <th><strong>Delete</strong></th>
      </tr>
    </thead>
    <tbody>
<?php

      $result = getAccounts();
      // Check for error
      if($result->error == false){
        // below line for data debug
        var_dump($result->data);
        echo "<br><br>";
        print_r($result);
      }else{
        // echo the error!
        echo $result->message;
      }
      $count=0;
      // $query="SELECT * FROM users;";
      // $result = $conn->query($query);
        foreach ($result as $row){
          $count += 1;
          // Same as below, maybe this is a bit easier to use?
          // echo '<tr>';
          // echo '<td align="center">' . $count . '</td>';
          ?>
        <tr>
          <td align="center"><?php echo $count; ?></td>
          <td align="center"><?php echo $row["userid"]; ?></td>
          <td align="center"><?php echo $row["username"]; ?></td>
          <td align="center"><?php echo $row["password"]; ?></td>
          <td align="center"><?php echo $row["admin"]; ?></td>
          <td align="center">
          <a href='edit.php?id="<?php echo $row["userid"];?>"'>Edit</a>
          </td>
          <td align="center">
          <a href='delete.php?id="<?php echo $row["userid"];?>"'>Delete</a>
          </td>
        </tr>

<?php
      }
    ?>
  </tbody>
</table>
<?php
    require 'include/footer.php';
?>
