<?php
  if (!empty($_GET['act'])) {
    shell_exec("sudo /home/snreloaded/restart.sh");
  } else {
?>
<h3>Restart Server</h3>
<form action="restart.php" method="get">
  <input type="hidden" name="act" value="run">
  <input type="submit" value="Restart Server">
</form>
<?php
  }
?>

