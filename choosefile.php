<?php
 if (!isset($include_HTML)) include ("include/HTML.php");
 include ("lang/$language.php");
 if ($frames=="yes") {
  echo getHTMLHeader();
  echo "</head><body>";
 }
?>


<center>
<h1 class="title"><?php echo $text_choose_file ?><h1><br>
<img src="images/logo.PNG">
</center>
<br><br><p align="right" class="footer"><?php echo $copyright ?></p>


<?php

 if ($frames=="yes") {
  echo "</body>";
  echo getHTMLFooter();
 }

?>