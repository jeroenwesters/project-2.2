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

<?php
    require 'include/footer.php';

?>
