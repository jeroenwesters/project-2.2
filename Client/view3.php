<?php
    require 'include/header.php';

    echo "These are the top 10 stations with the most rainfall<br><br>";

?>
<table id="t01" border="1px">
  <tr>
    <td>Station</td>
    <td>Rainfall</td>
  </tr>
<?php
$values = array
(
  array("Station1",15),
  array("Station2",13),
  array("Station3",11),
  array("Station4",10),
  array("Station5",8),
  array("Station6",6),
  array("Station7",6),
  array("Station8",5),
  array("Station9",4),
  array("Station10",2),
);
//displays table of values from array
$count = 0;
while($count < 10){
  echo "<tr>";
  echo "<td>",$values[$count][0],"</td>";
  echo "<td>",$values[$count][1]," mm</td>";
  echo "</tr>";
  $count++;
}
  echo "</table><br><br>";

  require 'include/footer.php';

?>
