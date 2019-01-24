<div class="right">
  <a href="logout.php">logout</a>
  <br>
  <?php
    if($_SESSION['admin'] == 1){ //only display the admin panel when the user has admin rights
      echo "<a href='admin.php'>admin panel</a>";
    }
   ?>
</div>
