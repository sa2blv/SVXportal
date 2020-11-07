<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "config.php";
set_time_limit(10000);

ini_set('mysql.connect_timeout','0');
ini_set('max_execution_time', '0');   


$mysqli = new mysqli( $host, $user, $password , $db);
if (mysqli_connect_errno()) { /* check connection */
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

//check if table exsist
/*
 * 
 * SELECT IF( EXISTS(
             SELECT value
             FROM Settings
             WHERE `Define` =  'PORTAL_VERSION'), 1, 0)
 * show tables like "Settings";
 */


$sql = "SHOW tables;";

$result = $mysqli -> query($sql);

// Numeric array
$tables = $result -> fetch_all(MYSQLI_ASSOC);



    


$tabel_data = Array();
$settings_nedel=0;
foreach($tables as $mydata => $value)
{


    if(in_array("Settings", $value))
    {
        $settings_nedel=1;
    }
}    




// check i auto exist

if($settings_nedel == 1) 
{

    $sql = "SELECT value FROM Settings where Define = 'PORTAL_VERSION'";
    $result = $mysqli -> query($sql);
    
    $data = $result -> fetch_array();

    if($data['value'] == "2.3")
    {
        $sql = file_get_contents('sql/update 2.4.sql');

        /* execute multi query */
        
        if ($mysqli->multi_query($sql))
        {
            echo "successfull upgrade to 2.4 db";

        }
        
        
        
    }
    else
    {
        echo "successfull upgrade  db";
        echo "pleace log in on admin.php with usernme svxportal and password svxportal and change the password";
        
    }


        
        
}
else
{

    
    $sql = file_get_contents('sql/update2-3.sql');
  
    /* execute multi query */

    if ($mysqli->multi_query($sql))
    {
        echo "successfull upgrade to 2.3 db";
        echo "pleace log in on admin.php with usernme svxportal and password svxportal and change the password";
    }
    
}

?>


