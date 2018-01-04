<?php
    require_once "check.php";
?>
<html>
  <head>
    <title>Home</title>
  </head>
  <body>
    <?php
       require "header.php"
    ?>
    <h1 class="hello">Welcome, <em><?php echo $_SESSION["username"];?></em><br /></h1>
  </body>    
</html>
