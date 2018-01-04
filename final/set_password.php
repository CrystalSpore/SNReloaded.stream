<?php
   require "check.php"
?>
<?php
   require "pass_check.php"
?>

<html>
  <head>
    <title>Set Password</title>
  </head>
  <body>
    <?php
       require "header.php"
    ?>

    <div id="body">
      <div class="user-form">
        <h1>Set Password</h1>
	<form action="" method="POST">
	  <input type="password" name="password" placeholder="password" required>
	  <br />
	  <input type="password" name="confirm-password" placeholder="confirm password" required>
	  <br />
	  <input type="submit" name="submit" value="Set Password">
	  <br />
	  <?php
	     echo "$error";
	     echo "$success";
	  ?>
	</form>
      </div>
    </div>
  </body>
</html>