<?php
include 'config.php';
session_start();
$username = $_POST['login'];
$password = $_POST['password'];

$username= $link->real_escape_string($username);
$password = $link->real_escape_string($password);

$password_hash = md5($password);


$result = mysqli_query($link, "SELECT id,is_admin,level  FROM `users`  WHERE Username = '".$username."' AND Password='".$password_hash."'  ");



while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
{

    if($row['id'] >0 )
    {
        $_SESSION["loginid"] = $row['id'];
        $_SESSION["level"] = $row['level'];
        $_SESSION["is_admin"] = $row['is_admin'];
        $_SESSION["Username"] = $username;
        echo "true";
    }
    else
    {
        echo "false";
    }
    

}


?> 