<?php
   if (session_id() == '')
   {
      session_start();
   }

   require_once "connect.php";

   $error = "";
   $success = "";

   if (isset($_POST["submit"]))
   {
      if ($_POST["password"] !== $_POST["confirm-password"])
      {
         $error ="Passwords don't match.";
      }
      else
      {
         $username = $_SESSION["username"];
         $password = $_POST["password"];

         $sql = "UPDATE users set password = ? WHERE username = ?;";
         $stmt = $db->prepare($sql);
         $stmt->bind_param("ss", $password, $username);

         if ($stmt->execute())
         {
            $success = "Password changed!";
         }
	 else
	 {
	    $error = "Error: Could not update password.";
	 }
      }
   }
?>
						