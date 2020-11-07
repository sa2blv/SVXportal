<?php
include "config.php";
include 'function.php';
include "Mqtt_driver.php";
define_settings();
set_laguage();

mysqli_set_charset($link,"utf8");

function scan_dir($dir) {
    
    
    $filelist = glob($dir."/*");
    
    
    return $filelist;
    
}



?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title> SVX Portal <?php echo PORTAL_VERSION ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<link rel="icon" type="image/png" href="tower.svg">
<link	href="https://fonts.googleapis.com/css?family=Architects+Daughter&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css.css">
<link href="dist/skin/blue.monday/css/jplayer.blue.monday.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="lib/jquery.min.js"></script>
<script type="text/javascript" src="dist/jplayer/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="dist/add-on/jplayer.playlist.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>
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
<script src="https://cdn.tiny.cloud/1/<?php echo API_KEY_TINY_CLOUD;?>/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

<style type="text/css">
.hideMe {
    display: none;
}




</style>

<script src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js"></script>
<link rel="stylesheet" href="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/css/ol.css">
<style>
      .map {
        width: 100%;
        height:600px;
      }
</style>


<link rel="stylesheet" href="jquery-ui.css">
<script src="jquery-ui.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>
    
    
<script type="text/javascript">




tinymce.init({
  selector: '#mytextarea',
  plugins: 'print preview paste importcss searchreplace autolink directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
  removed_menuitems: 'newdocument',
  height : "800"
});

$('#mytextarea_Dialog').on('resized', function (event) {
    var hgt= $('#mytextarea_Dialog').jqxWindow("height");
    //alert("window:resized. height=" + hgt);
    tinymce.activeEditor.theme.resizeTo(null, hgt-150);
});




tinymce.init({
	  selector: '#Hardware_area',
	  plugins: 'print preview paste importcss searchreplace autolink directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
	  removed_menuitems: 'newdocument',
	  height : "800"
	});

	$('#Hardware_area_Dialog').on('resized', function (event) {
	    var hgt= $('#Hardware_area_Dialog').jqxWindow("height");
	    //alert("window:resized. height=" + hgt);
	    tinymce.activeEditor.theme.resizeTo(null, hgt-150);
	});




function save_information()
{
	$('#' + 'mytextarea').html( tinymce.get('mytextarea').getContent() );
	$.post( "admin/save_edit_page.php", $( "#information_form" ).serialize() )
	.done(function( data ) {
		alert("<?php echo _('Page is saved')?>");




	});
	
}



function save_Hardware()
{
	$('#' + 'Hardware_area').html( tinymce.get('Hardware_area').getContent() );
	$.post( "admin/save_edit_page.php", $( "#Hardware_form" ).serialize() )
	.done(function( data ) {
		alert("<?php echo _('Page is saved')?>");




	});
	
}
function save_setings()
{
	$.post( "admin/save_edit_page.php", $( "#Station_edit_settings" ).serialize() )
	.done(function( data ) {
		alert("<?php echo _('Settings is saved')?>");
		 location.reload(); 




	});
	return false;
	
}
function Create_ticket()
{
	$.post( "admin/save_edit_page.php", $( "#create_ticket" ).serialize() )
	.done(function( data ) {
		alert("<?php echo _('Ticket is created')?>");
		location.reload(); 




	});
	return false;
	
}








<?php

		if($use_mqtt == true){
		    
		    $mytt_man = new MQtt_Driver;
		    $mytt_man-> Set_broker($mqtt_host,$mqtt_port,$mqtt_TLS);
		    
		    $mytt_man->javascipt();
		    
		    $mytt_man->Print_hock_on_message();
		    
//		    $mytt_man->

		    
		}else
		{
		    
		    // Create a dummu fumction    
		 ?>
		    function MQTTconnect()
		    {
		    

			}
		 <?php  
		}

		?>





$( document ).ready(function() {

	MQTTconnect();
});






</script>

</head>





<body>
<?php if( $_SESSION['loginid'] >0 ){?>



<?php 
/*
 * Part of code that load the page
 */




$idnr = $_GET["Station_idnr"];
$idnr = $link->real_escape_string($idnr);







if($_GET["Station_idnr"])
{
    
    

    
    $result = mysqli_query($link, "SELECT Html , Hardware_page, id, Station_Name, Station_id, Module, Image FROM `Infotmation_page` WHERE Station_id  ='".$idnr."'");
    
    
    
    
}
else
{
 echo _("No station id");
 exit(-1);
 
}



/*
 * 
 * Loading driver for dashnboard
 * 
 */
$station_data = mysqli_fetch_array($result, MYSQLI_ASSOC);




if($station_data['Module'] !="")
{
    $module = $station_data['Module'];
    __autoload_driver($module);
    
    $driver = new $module();
    $driver->Init($station_data['Station_Name']);
    


}


/*
 * Check for premission
 * 
 * 
 */


if(check_premission_station($_GET["Station_idnr"],$_SESSION['loginid']) == 0)
{
 echo '<h1>'._('Premission Denied!').'</h1>';
 exit(-1);   
}
$premission_rw =check_premission_station_RW($_GET["Station_idnr"],$_SESSION['loginid']);

?>





    
<header>
     <nav class="navbar   navbar-dark sidebar_collor justify-content-between">
      
       
        <div>
   <a class="navbar-brand" href="#">
    <img src="loggo.png" alt="Logo" style="width:40px;">

  </a>

   <a class="navbar-brand wite_font" href="#">
   
     SVX Portal <?php echo _('Station Dashboard')?>
   </a>
   
   </div>
   
   
   
   
   

      <a href="requset_reflector_login.php" onclick="" class="btn btn-outline-success my-2 my-sm-0" id="menu-toggle"><?php echo _('Back') ?></a>
      
    </nav> 
</header>
        

 <div>
 
 <?php     $driver->Dashboard();?>
 
 
 </div>
  

  
  <?php }else{
    include 'admin/login.php'; }?>

</body>
</html>
