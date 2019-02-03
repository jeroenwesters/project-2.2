<header>
  <?php
  $url1 = "http://" . $_SERVER['SERVER_NAME'] .'/'. 'index.php';
  $url2 = "http://" . $_SERVER['SERVER_NAME'] .'/'. 'view1.php';
  $url3 = "http://" . $_SERVER['SERVER_NAME'] .'/'. 'view2.php';
  $url4 = "http://" . $_SERVER['SERVER_NAME'] .'/'. 'view3.php';
  $url5 = "http://" . $_SERVER['SERVER_NAME'] .'/'. 'account.php';
  $url6 = "http://" . $_SERVER['SERVER_NAME'] .'/'. 'admin.php';
  $url7 = "http://" . $_SERVER['SERVER_NAME'] .'/'. 'logout.php';

  $img = "http://" . $_SERVER['SERVER_NAME'] .'/'. 'pictures/vectorpaint2.svg';
  ?>

  <li class="comp_logo"><a href="<?php echo $url1;  ?>"><img src='<?php echo $img ?>' alt="Home"></a></li>
  <li><a href="<?php echo $url2;  ?>">Ukraine</a></li>
  <li><a href="<?php echo $url3;  ?>">Scandinavia</a></li>
  <li><a href="<?php echo $url4;  ?>">Top 10</a></li>
  <li style="float:right"><a href="<?php echo $url5;  ?>">Logout</a></li>
  <li style="float:right"><a href="<?php echo $url6;  ?>">Admin Panel</a></li>
  <li style="float:right"><a href="<?php echo $url7;  ?>">Account</a></li>
</header>
