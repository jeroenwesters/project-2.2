</div><!--closes body div-->
<div class="footer"> <!-- opens div for the footer-->

<!-- Show copyright icon and php for the year-->
  <p align="center"> &copy;
    <?php
  if(Date("Y") == 2019) {
    echo "2019 ";
  } else {
    echo "2019-" . Date("Y") . " ";
  }
  ?>
  Project 2.2 </p>

</div> <!-- closes div for footer-->

</body> <!-- close body-->
</html> <!-- close html -->
