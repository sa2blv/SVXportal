<?php
header('Access-Control-Allow-Origin: <?php echo $serveradress ?>');
include "config.php";
include 'function.php';
$fault_counter =0;

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title> SVX Portal Self test</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<link rel="icon" type="image/png" href="tower.svg">
<link	href="https://fonts.googleapis.com/css?family=Architects+Daughter&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css.css">
<link href="dist/skin/blue.monday/css/jplayer.blue.monday.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="lib/jquery.min.js"></script>
<script type="text/javascript" src="dist/jplayer/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="dist/add-on/jplayer.playlist.min.js"></script>
<link rel="stylesheet" href="lib/css/bootstrap.min.css"
	integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
	crossorigin="anonymous">
<!-- Optional theme -->

<!-- Latest compiled and minified JavaScript -->

<script src="./lib/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="lib/css/bootstrap.min.css">


<link rel="stylesheet" href="jquery-ui.css">
<script src="jquery-ui.js"></script>

<script
	src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js"></script>
<link rel="stylesheet"
	href="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/css/ol.css">
<!-- fontawesome.com iconpac -->
<link href="./fontawesome/css/all.css" rel="stylesheet">
<!--load all styles -->
<script src="./js/chart/Chart.min.js"></script>

<style type="text/css">
.hideMe {
    display: none;
}




</style>




   <header>
 <nav class="navbar   navbar-dark sidebar_collor">
 <div>
   <a class="navbar-brand" href="#">
    <img src="loggo.png" alt="Logo" style="width:40px;">

  </a>

   <a class="navbar-brand wite_font" href="#">
   
     SVX Portal Selftest
   </a>
   
   </div>

  <div class="topnav-right" >
  <a href=""" onclick="window.close()" class="btn btn-outline-success my-2 my-sm-0" id="menu-toggle"><?php echo _('Back')?></a>
  </div>
</nav> 
    </header>
    

<div class="container-fluid">





<?php 

echo "<h2>Test 1 Database</h2><br />";

if ($result = $link->query("SELECT Define FROM `Settings`")) {
    
    /* determine number of rows result set */
    $row_cnt = $result->num_rows;
    
    printf("Test <b class='text-success'>Suscess</b> found %d rows in settings.\n", $row_cnt);
    
    /* close result set */
    $result->close();
}
else
{
    
    echo "Test <b class='text-danger'>Fail</b> Check database<br />";
    $fault_counter++;
}

echo "<h2>Test 2 Reflector </h2><br />";


$ctx = stream_context_create(array('http'=>
    array(
        'timeout' => 1200,  //1200 Seconds is 20 Minutes
    )
));

$json_test = file_get_contents($serveradress, false, $ctx);

if($json_test == "")
{
    echo "<b class='text-danger'>Fail</b> no contact to proxy check serveradress in config.php <b>HTTP_SRV_PORT</b> in svxreflector.conf and <b>reflector_proxy/config.php url fail</b> ";
    
    $fault_counter++;
    
}
else
{
    $data = @json_decode($json_test);
    
    if ($data === null
        && json_last_error() !== JSON_ERROR_NONE) {
            echo "<b class='text-danger'>Fail</b> Proxy not working check  s <b>HTTP_SRV_PORT</b> in svxreflector.conf and <b>reflector_proxy/config.php</b> ";
            $fault_counter++;
            
   }
   else
   {
       

       echo "<b class='text-success'>Sucsess</b> json decoded <br /><br />";

       

       $json_data = file_get_contents($serveradress,false,$context);
       
       
       //$json_data = file_get_contents($serveradress);
       $json_data = iconv("utf-8", "utf-8//ignore", $json_data);
       $data = json_decode($json_data);
       
       echo "<p>Found folowing stations</p>";
       echo "<ul>";
       
       foreach($data->nodes as $st => $station)
       {
       
           echo "<li>".$st."</li>";

       }
       echo "</ul>";
       
       
   }
       
        
        
    
 
}


if($fault_counter == 0)
{
    echo "<p> All test <b class='text-success'>sucess</b> go to admin to change settings </p>";
}













?>

</div>

</body>
</html>


