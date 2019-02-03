<header>
  <?php
  // $url1 = "http://" . $_SERVER['SERVER_NAME'] .'/'. 'index.php';
  // $url2 = "http://" . $_SERVER['SERVER_NAME'] .'/'. 'scandinavia-overview.php';
  // $url3 = "http://" . $_SERVER['SERVER_NAME'] .'/'. 'scandinavia-top.php';
  // $url4 = "http://" . $_SERVER['SERVER_NAME'] .'/'. 'view3.php';
  // $url5 = "http://" . $_SERVER['SERVER_NAME'] .'/'. 'account.php';
  // $url6 = "http://" . $_SERVER['SERVER_NAME'] .'/'. 'admin.php';
  // $url7 = "http://" . $_SERVER['SERVER_NAME'] .'/'. 'logout.php';

  $url1 = 'index.php';
  $url2 = 'scandinavia-overview.php';
  $url3 = 'scandinavia-top.php';
  $url4 = 'view3.php';
  $url5 = 'account.php';
  $url6 = 'admin.php';
  $url7 = 'logout.php';

  $img = "http://" . $_SERVER['SERVER_NAME'] .'/'. 'pictures/vectorpaint2.svg';
  ?>

  <li class="comp_logo"><a href="<?php echo $url1;  ?>"><img src='<?php echo $img ?>' alt="Home"></a></li>
  <li class="hover_effect"><a href="<?php echo $url2;  ?>">Scandinavia overview</a></li>
  <li class="hover_effect"><a href="<?php echo $url3;  ?>">Scandinavia top 10</a></li>
  <li class="hover_effect"><a href="<?php echo $url4;  ?>">Ukraine real-time</a></li>
  <li class="hover_effect" style="float:right"><a href="<?php echo $url5;  ?>">Logout</a></li>
  <li class="hover_effect" style="float:right"><a href="<?php echo $url6;  ?>">Admin Panel</a></li>
  <li class="hover_effect" style="float:right"><a href="<?php echo $url7;  ?>">Account</a></li>
</header>
