<?php
include '../config.php';
include '../function.php';
include "../Mqtt_driver.php";
include 'genhash.php';
define_settings();
set_laguage();

// Create connection
$conn = new mysqli($mosquito_auth_db_host, $mosquito_auth_db_user, $mosquito_auth_db_password, $mosquito_auth_db_db);

mysqli_set_charset($conn, "utf8");

if ($_SESSION['is_admin'] > 0 && $_SESSION['loginid'] > 0) 
{

    $password = $_POST['password'];
    $node = $_REQUEST['node'];
    $node = $conn->real_escape_string($node);



    $action = $_GET['action'];

    if ($action == "Create") 
    {

        $pass_hash = create_hash($password);
       

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $lines = file('mqtt_topic_template.txt');

        foreach ($lines as $line)
        {

            $line = str_replace("%NODE%", $node, $line);

            $row = explode("\t", $line);
            $rw = 0;
            var_dump($row['1']);
            if (strpos($row['1'], "rw") !== false) {
                $rw = 1;
            }

            $sql = "INSERT INTO `acls`( `username`, `topic`, `rw`) VALUES ('$node','" . $row['0'] . "','$rw')";
            $result = $conn->query($sql);
        }

        $user_sql = "INSERT INTO `users` (`id`, `username`, `pw`, `super`) VALUES (NULL, '$node', '$pass_hash', '0');";
        $result = $conn->query($user_sql);
    } 
    else if ($action == "Restore_acl") 
    {

        $sql_d = " DELETE FROM `acls` WHERE username= '$node'";

        $conn->query($sql_d);
        $lines = file('mqtt_topic_template.txt');
        foreach ($lines as $line)
        {

            $line = str_replace("%NODE%", $node, $line);

            $row = explode("\t", $line);
            $rw = 0;
            var_dump($row['1']);
            if (strpos($row['1'], "rw") !== false)
            {
                $rw = 1;
            }

            $sql = "INSERT INTO `acls`( `username`, `topic`, `rw`) VALUES ('$node','" . $row['0'] . "','$rw')";
            $result = $conn->query($sql);
        }
    }
    else if ($action == "Restore_Password")
    {

      
        $pass_hash = create_hash($password);
        $sql = "UPDATE `users` SET `pw` = '$pass_hash' WHERE `users`.`username` = '$node'; ";

        $result = $conn->query($sql);
        
    }
    
    else if ($action == "Add_topic")
    {
        
        
        $pass_hash = create_hash($password);
        
        $topic = $conn->real_escape_string($_POST['topic']);
        $rw = $conn->real_escape_string($_POST['rw']);
        
        $sql = "INSERT INTO `acls` (`id`, `username`, `topic`, `rw`) VALUES (NULL, '$node', '$topic', '$rw');";


        $result = $conn->query($sql);
        
    }
    
    else if ($action == "remove_topic")
    {
        
        

        
        $id = $conn->real_escape_string($_POST['id']);

        
        $sql_d =  "DELETE FROM `acls` WHERE username= '$node' and id='$id'";

     
        $result = $conn->query($sql_d);
        
    }
    
    else if ($action == "PROVISION")
    {
        
        

        
        
        $conn->close();
        $resulta = mysqli_query($link, "SELECT * FROM `RefletorStations` ");
        
        // Numeric array
        
        // Associative array
        while ($rowa = mysqli_fetch_array($resulta))
        {
            
            
            $conn = new mysqli($mosquito_auth_db_host, $mosquito_auth_db_user, $mosquito_auth_db_password, $mosquito_auth_db_db);
            
            mysqli_set_charset($conn, "utf8");
            
            
            
            

            
            $node =$rowa['Callsign'];
            
           

        
    
            $sql_d = " DELETE FROM `acls` WHERE username= '".$rowa['Callsign']."'";
            echo $sql_d;
            
            $conn->query($sql_d);
            
            
            $lines = file('mqtt_topic_template.txt');
            foreach ($lines as $line)
            {
                
                $line = str_replace("%NODE%", $node, $line);
                
                $row = explode("\t", $line);
                $rw = 0;
  
                if (strpos($row['1'], "rw") !== false)
                {
                    $rw = 1;
                }
                
                $sql = "INSERT INTO `acls`( `username`, `topic`, `rw`) VALUES ('$node','" . $row['0'] . "','$rw')";
                
       
                $result = $conn->query($sql);
            }
            
                
        }
        echo "Done";
        $conn->close();
        
    }
    else if ($action == "ADD_TO_ALL")
    {
        $line =urldecode( $_REQUEST['line']);
        
        echo "Adding ".$line ." to All Nodes <br />";
        
        
        
        $line = $conn->real_escape_string($line);
        
        
        $conn->close();
        $resulta = mysqli_query($link, "SELECT * FROM `RefletorStations` ");
        
        // Numeric array
        
        // Associative array
        while ($rowa = mysqli_fetch_array($resulta))
        {
            
            $node =$rowa['Callsign'];

            $line1 = str_replace("%NODE%", $node, $line);
            
            $conn1 = new mysqli($mosquito_auth_db_host, $mosquito_auth_db_user, $mosquito_auth_db_password, $mosquito_auth_db_db);
            
            mysqli_set_charset($conn1, "utf8");
            
            
        
            
            echo $line1 ."<br />";

            if (strpos($row['1'], "rw") !== false)
            {
                $rw = 1;
            }
            
            $sql = "INSERT INTO `acls`( `username`, `topic`, `rw`) VALUES ('$node','" . $line1 . "','0')";
            
             $conn1->query($sql);
             $conn1->close();
        
            
            
        }
        echo "Done";
        $conn->close();
        
    }
    
    
    
    
    
    
    
    
    

    
}else
{

    
    
}


if($_SESSION['loginid'] > 0)
{
    
    if ($action == "Restore_Password" && check_premission_station_RW($_GET["Station_idnr"],$_SESSION['loginid']) >0 )
    {
        
        
        $pass_hash = create_hash($password);
        
        $user_sql = "";
        
        
        
        $sql = "
   INSERT INTO users (username,pw,super)
VALUES ( '$node', '$pass_hash', '0')
ON DUPLICATE KEY UPDATE
	pw= '$pass_hash';
        ";
        
        echo $sql;
        
 

        $result = $conn->query($sql);
        echo "11a";
        
    }
    
}




$conn->close();
