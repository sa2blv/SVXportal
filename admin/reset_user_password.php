<?php 
include "../config.php";
include '../function.php';
define_settings();
mysqli_set_charset($link,"utf8");
error_reporting(E_ALL);
error_reporting(-1);
ini_set('error_reporting', E_ALL);


function send_mail($email,$token)
{
    
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        $url = "https://";
    else
        $url = "http://";
    // Append the host(domain name, ip) to the URL.
    $url.= $_SERVER['HTTP_HOST'];
    
    // Append the requested resource location to the URL
    $url.= $_SERVER['REQUEST_URI'];
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            
            
    

        $subject = "Svxportl Password reset ";

        // If strict types are enabled i.e. declare(strict_types=1);
        $message = file_get_contents('password_mail.htm', true);

        $message  = str_replace("%URL%", $url.'?token='.$token , $message);
        

        $message .= '</body></html>';
        

   
    /*$headers = 'From: '.SYSTEM_MAIL.'' . "\r\n" .
        'Reply-To:'.SYSTEM_MAIL.'' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    */
        $from = SYSTEM_MAIL;
        
        $headers .= 'From: '.$from."\r\n".
            'Reply-To: '.$from."\r\n" .
            'X-Mailer: PHP/' . phpversion();
        
        

    
    if($email !="")
    {
        mail($email, $subject, $message,$headers);
    }
    

    
    
    
    
}





if($_GET['token'] != "")
{

    
    $token= $link->real_escape_string(trim($_GET['token']));
    

    
    $result = mysqli_query($link, "SELECT * FROM `users` where  `Reset_token` = '$token'  ");
    
    
    // get user row
    $row = $result->fetch_assoc();
    $urid=  $row['id'];
    
    $_SESSION["loginid"] = $row['id'];
    $_SESSION["Username"] = $row['Username'];
    $_SESSION["User_image"] = $row['image_url'];

    $link->query("UPDATE `users` SET `Reset_token` = '' WHERE `users`.`id` = '$urid'; ");
    $link->commit();
    
    
    header("Location: ../user_settings.php?password=1");
    
    


    
    
    
}
else
{
    
    
    
    
    $Reset_password= $link->real_escape_string(trim($_POST['Reset_password']));
    

    
    
    
    $result = mysqli_query($link, "SELECT * FROM `users` where  `username` = '$Reset_password'  ");
    
    
    $row = $result->fetch_assoc();
        
        
    
    


    

    $urid =$row['id'];
    
    if(!$urid)
    {
        echo "2";
        exit(0);
    }

/*
 * Create user string 
 * 
 * 
 */

    
    
$link->begin_transaction();
$link->autocommit(FALSE);



$bytes = bin2hex(random_bytes(35));

$link->query("UPDATE `users` SET `Reset_token` = '$bytes' WHERE `users`.`id` = '$urid'; ");
$link->commit();


send_mail($row['email'],$bytes);
echo "1";
}






?>