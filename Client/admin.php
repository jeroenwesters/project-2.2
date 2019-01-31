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
        <th><strong>ID</strong></th>
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
        $count=0;
        foreach ($result->data as $row){
          $count += 1;
          echo "<tr>";
            echo"<td align='center'>".$count."</td>";
            echo"<td align='center'>".$row["userid"]."</td>";
            echo"<td align='center'>".$row["username"]."</td>";
            echo"<td align='center'>".$row["password"]."</td>";
            echo"<td align='center'>".$row["admin"]."</td>";
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

    require 'include/footer.php';
?>
