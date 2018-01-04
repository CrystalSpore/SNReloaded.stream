<html>
    <head>
        <title>Login</title>
    </head>
    <p>Incorrect Username/Password Combination... <a href="./index.html">Return</a></p>
</html>
<?php
    //var_dump('$_POST');
    session_start();
    require_once "connect.php";
    $error = "";
    
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $sql = "SELECT username FROM users WHERE username='$username' AND password='$password'";

    $result = $db->query($sql);

    //Check that there is exactly 1 matching username and password
    if ($result->num_rows === 1)
    {
        echo "<p>it worked</p>";
        $_SESSION["username"] = $username;
        header("Location: home.php");
     }
?>
