<?php

   require_once "connect.php";

   session_start();

   $check = $_SESSION["username"];

   if (!isset($check))
   {
      header("Location: index.php");
   }
   $student = $check;
?>