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
	get_table();
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

$result = mysqli_query($link, "SELECT Callsign FROM `RefletorStations` where ID='".$idnr."'");

$user_id= $_SESSION['loginid'];

// Associative array
$row_id = mysqli_fetch_array($result, MYSQLI_ASSOC);

function create_station($station_id) {
    global $link;
    
    
    $res = mysqli_query($link, "SELECT Callsign, ID FROM `RefletorStations` WHERE ID='".$station_id."'");
    
    $temp = mysqli_fetch_array($res, MYSQLI_ASSOC);
    
 
    
    $calls = $temp['Callsign'];
    $idnr =$temp['ID'];
    

    
    if($temp != null)
    {

        $default_info ="";
        $default_hw ="";
        
        
     
        $handle = @fopen("./driver/core/DefaultHardware.php", "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $default_hw.= $buffer;
            }
            if (!feof($handle)) {
               
            }
            fclose($handle);
        }

        
        // Outputs an empty string
        $default_hw= htmlentities(($default_hw), ENT_QUOTES | ENT_IGNORE, "UTF-8");
       //$default_hw= ;
        $default_hw = $link->real_escape_string($default_hw);
        

        
        
        
        
        
        
        mysqli_query($link, "INSERT INTO `Infotmation_page` (`id`, `Station_Name`, `Station_id`, `Module`, `Html`, `Hardware_page`, `Image`) VALUES (NULL, '$calls', '$idnr', '', '$default_info', '$default_hw', '');");
    }
    
}



if($_GET["Station_idnr"])
{
    
    
    $call =$row_id['Callsign'];
    $call = $link->real_escape_string($call);
    
    
    $result = mysqli_query($link, "SELECT Html , Hardware_page, id, Station_Name, Station_id, Module, Image FROM `Infotmation_page` WHERE Station_Name='".$call."'");
    
    
    
    
}
else
{
    
    $id = $_GET["Station_id"];
    $id = $link->real_escape_string($id);
    $result = mysqli_query($link, "SELECT Html , Hardware_page, id, Station_Name, Station_id, Module, Image, GrafanaUrl FROM `Infotmation_page` WHERE id='".$id."'");
    
    
    
}




$station_data = mysqli_fetch_array($result, MYSQLI_ASSOC);



if($station_data == NULL)
{
    if($_GET["Station_idnr"])
    {
        create_station($_GET["Station_idnr"]);
        $result = mysqli_query($link, "SELECT Html , Hardware_page, id, Station_Name, Station_id, Module, Image FROM `Infotmation_page` WHERE Station_Name='".$call."'");
        $station_data = mysqli_fetch_array($result, MYSQLI_ASSOC);
        
    }
        
}

/*
 * 
 * Loading driver for dashnboard
 * 
 */
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
   
     SVX Portal <?php echo _('Station editor')?>
   </a>
   
   </div>
   
   
   
   
   

      <a href="requset_reflector_login.php" onclick="" class="btn btn-outline-success my-2 my-sm-0" id="menu-toggle"><?php echo _('Back') ?></a>
      
    </nav> 
</header>
        
        
        
    
 <div class="container-fluid">
 
 <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo _('Information')?></a>
  </li>
  <li class="nav-item">
    <a class="nav-link " id="Hardware-tab" data-toggle="tab" href="#Hardware" role="tab" aria-controls="Hardware" aria-selected="true"><?php echo _('Hardware')?></a>
  </li>

  <li class="nav-item">
    <a class="nav-link " id="DTMF-tab" data-toggle="tab" href="#DTMF" role="tab" aria-controls="DTMF" aria-selected="true"><?php echo _('DTMF commands')?></a>
  </li>  

 <li class="nav-item">
  <a class="nav-item nav-link" id="Status-tab" data-toggle="tab" href="#Status" role="tab" aria-controls="Status" aria-selected="false"><?php echo _('Operating information')?></a>
  </li>
  <?php 
  
  if($station_data['Module'] !="")
  {
  ?>
  
  
  <li class="nav-item">
  <a class="nav-item nav-link" id="Dirver-tab" data-toggle="tab" href="#Dirver" role="tab" aria-controls="Dirver" aria-selected="false"><?php echo _('Driver dashboard')?></a>
  </li>
  
  <?php }?>
<?php if($premission_rw >0 ){?>
 	<li class="nav-item">
    	<a class="nav-link" id="config-tab" data-toggle="tab" href="#config" onclick="" role="tab" aria-controls="log" aria-selected="false"><?php echo _('Settings')?></a> 
	</li>
  <?php }?>

</ul>


 
 <div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">


<nav class="navbar navbar-expand-sm navbar-light  bg-light" style="background-color: #e3f2fd;">
		
		
				
				
				<ul class="navbar-nav mr-auto mt-2 mt-lg-0">


      
         <li class="nav-item d-none d-lg-inline-flex">
        
        <?php if($premission_rw >0 ){?>
        <li class="nav-item d-none d-lg-inline-flex">

            <a class="nav-link  " href="#" id="navbarDropdownMenuLink" onclick="save_information()" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <i class="fas fa-save"></i>
              <?php echo _('Save')?>        </a>         
        </li>
        <?php }?>



    </ul>
    
 

      
</nav>





<form method="post" id="information_form">
      <div>
      	<textarea id="mytextarea" name="station"><?php echo  $station_data['Html'];?></textarea>
  	      	<input type="hidden" name="id" value="<?php echo $station_data['id']?>">
      	<input type="hidden" name="Information_edit" value="1">
      </div>
</form>
  
  
  
  



  <div class="modal fade" id="command_to_node" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><?php echo _('Send command to node')?></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">
         
     <form onsubmit="return send_command_to_node()" id="node_form_command">
          <div class="form-group">
            <label for="command"><?php echo _('DTMF command')?></label>
            <input type="text" name="command" class="form-control" id="command" aria-describedby="emailHelp" placeholder="">
          </div>
          
          <input type="hidden" name="Station_id" class="form-control" id="Station_id" value="">
  
          
          <div class="form-group">
            <label for="datepicker"><?php echo _('Scheduled date')?></label>
            <input type="text" id="datepicker" name="date" class="form-control" id="exampleInputEmail1" aria-describedby="" value="<?php echo date ("Y-m-d H:i:s")?>">
          </div>
          
          <button type="submit" class="btn btn-primary"><?php echo _('Send commnd to repeater')?></button>
    </form>



        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>

    
    
    
  </div>

  

  
 </div>
 
 
     
<div class="tab-pane fade show " id="Hardware" role="tabpanel" aria-labelledby="Hardware-tab">



<nav class="navbar navbar-expand-sm navbar-light  bg-light" style="background-color: #e3f2fd;">
		
		
				
				
				<ul class="navbar-nav mr-auto mt-2 mt-lg-0">


      
         <li class="nav-item d-none d-lg-inline-flex">
        

        <?php if($premission_rw >0 ){?>
        <li class="nav-item d-none d-lg-inline-flex">

            <a class="nav-link  " href="#" id="navbarDropdownMenuLink" onclick="save_Hardware()" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <i class="fas fa-save"></i>
              <?php echo _('Save')?>        </a>         
        </li>
        <?php }?>
             


        
        
        
        
        
        
        
        


    </ul>
    
 

      
</nav>



<form method="post" id="Hardware_form">
      <div>
      	<textarea id="Hardware_area" name="hardware"><?php echo  $station_data['Hardware_page'];?></textarea>
      	<input type="hidden" name="id" value="<?php echo $station_data['id']?>">
      	<input type="hidden" name="Hardware_edit" value="1">
      </div> 
</form>


    

    
    
</div>
    
    <?php 
    /*
     *   Fada tab
     */
  
    
    ?>
    <div class="tab-pane fade show" id="Status" role="tabpanel" aria-labelledby="Status-tab">
    
        <nav class="navbar navbar-expand-sm navbar-light  bg-light" style="background-color: #e3f2fd;">
		
		
				
				
				<ul class="navbar-nav mr-auto mt-2 mt-lg-0">


      
         <li class="nav-item d-none d-lg-inline-flex">
        
        <?php if($premission_rw >0 ){?>
        <li class="nav-item d-none d-lg-inline-flex">

            <a class="nav-link  " href="#" id="navbarDropdownMenuLink"  data-toggle="modal" data-target="#NedStatus"   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-file"></i>
              <?php echo _('New')?>        </a>         
        </li>
        <?php }?>



    </ul>
    
 

      
</nav>

<div class="modal fade" id="NedStatus" tabindex="-1" role="dialog" aria-labelledby="NedStatus" aria-hidden="true">
  <div class="modal-dialog" role="document">
     <form id="create_ticket" onsubmit="return Create_ticket()">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"><?php echo _('New ticket')?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
      
      
    
		  <input type="hidden" name="Insert_station_status" class="form-control" id="Insert_station_status" value="1">
          <input type="hidden" name="Station_id" class="form-control" id="Station_id" value="<?php echo $station_data['Station_id'];?>">
          
          <div class="form-group">
            <label for="text"><?php echo _('Text')?></label>
            <input type="text" name="command" class="form-control" id="text" aria-describedby="emailHelp" placeholder="">
          </div>
          
          
          <div class="form-group">
	      <select name="Type" id="Type"  class="form-control form-control-lg">
                  <option value="0" selected="selected"><?php echo _('Info')?></option>
                  <option value="1"><?php echo _('Warning')?></option>
                  <option value="2"><?php echo _('Fault')?></option>
                  <option value="3"><?php echo _('Done')?></option>
          </select>
          </div>
          
  
          
          <div class="form-group">
            <label for="datepicker"><?php echo _('Date')?></label>
            <input type="text" id="" name="date" class="form-control" readonly="readonly" id="datefield_ticket" aria-describedby="" value="<?php echo date ("Y-m-d H:i:s")?>">
          </div>
      
     

    
       
       
       
     
     
     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo _('Close')?></button>
        <button type="submit" class="btn btn-primary" "><?php echo _('Create')?></button>
      </div>
      </form>
    </div>
  </div>
</div>




    
           <table class="table table-striped ">
          <thead class=" thead-dark">
            <tr>
              <th style="width:30%"  ><?php echo _('Date')?></th>
              <th style="width:50%"   ><?php echo _('Message')?></th>
              <th style="width:20%"  ><?php echo _('Category')?></th>
            </tr>
          </thead>
          <tbody>
        
        <?php
    
        $result = mysqli_query($link, "SELECT * FROM `Operation_log` where Station_id = '".$station_data['Station_id']."' ORDER BY  Date  DESC  LIMIT 30  ");
        
        // Associative array
        $Operation_message_type = Operation_message_type();
        
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
        {
        
           ?>
           
           <tr>
           <td class="" >
            <?php echo $row['Date'];?>
           </td>
           <td class="">
           <?php echo $row['Message'];?>
           </td>
           
            <td class="">
           <?php echo $Operation_message_type[$row['Type']];?>
           </td>
           
           </tr>
            
        <?php   
        }
        ?>	
        
          </tbody>
        </table>
        
        
    </div>
    <?php 

/*
 * 
 * Configuration tab 
 * 
 * 
 * 
 */



?>
<div class="tab-pane fade show" id="config" role="tabpanel" aria-labelledby="config-tab">

<?php if($premission_rw >0 ){?>
 <form action="#" id="Station_edit_settings"  onsubmit="return save_setings()">
  <div class="form-group">
  
  <input type="hidden" name="update_setings" value="1">
  <input type="hidden" name="Update_id" value="<?php echo $station_data['id']?>">
  
  
  
    <label for="image"><?php echo _('Radio Image')?>:</label>
      <select class="custom-select" id="imgage" name="radio_image">
            <option selected value=""><?php echo _('None')?></option>
     
            <?php 
            $svx_folder="driver/images";
            
            $files = (scan_dir($svx_folder));
           
            foreach($files as $key => $val)
            {   
                $val  = str_replace("driver/images/", "", $val);
                
                $selected ="";
                if($station_data['Image'] == $val)
                {
                    $selected ="Selected=\"Selected\"";
                    
                }
                
                echo  '<option '.$selected.' value="'.$val.'">'.$val.'</option>';
             

            }

          
            ?>
	 </select>

  </div>
  <div class="form-group">
    <label for="Driver"><?php echo _('Driver')?>:</label>
      <select class="custom-select" id="Driver" name="Driver">
            <option selected value=""><?php echo _('None')?></option>
     
            <?php 
            $svx_folder="driver/card";
            
            $files = (scan_dir($svx_folder));
           
            foreach($files as $key => $val)
            {   
                $val  = str_replace("driver/card/", "", $val);
                $val  = str_replace(".php", "", $val);
                
                $selected ="";
                if($station_data['Module'] == $val)
                {
                    $selected ="Selected=\"Selected\"";
                    
                }
                
                
                
                echo  '<option '.$selected.' value="'.$val.'">'.$val.'</option>';
             

            }

          
            ?>
	 </select>
   </div>
   
   <div class="form-group">
    <label for="GrafanaUrl"><?php echo _('Graphana url')?>:</label>
    <input type="url" class="form-control" id="GrafanaUrl" name="GrafanaUrl" aria-describedby="emailHelp" placeholder="http://" value="<?php echo $station_data['GrafanaUrl']?>">
    
    
    
    
    

   </div>
   
   

  <button type="submit" class="btn btn-primary"><?php echo _('Save')?></button>
</form> 
<?php }?>


</div>



 <?php 
 
 /*
  *
  * Driver PAGE
  *
  */
 
 if($station_data['Module'] !="")
 {
    
?>
 <div class="tab-pane fade show " id="Dirver" role="tabpanel" aria-labelledby="Dirver-tab">

<?php     $driver->Dashboard_Settings();?>
 </div>
 
 
 
<?php }?>





<?php 

/*
 * 
 * DtmF PAGE
 * 
 */


?>
    
    
    
    <div class="tab-pane fade show " id="DTMF" role="tabpanel" aria-labelledby="DTMF-tab">
    
    
    <nav class="navbar navbar-expand-sm navbar-light  bg-light" style="background-color: #e3f2fd;">
		
		
				
				
				<ul class="navbar-nav mr-auto mt-2 mt-lg-0">


      
         <li class="nav-item d-none d-lg-inline-flex">
        <?php if($premission_rw >0 ){?>
            <li class="nav-item d-none d-lg-inline-flex">
    
                <a class="nav-link  " href="#" id="navbarDropdownMenuLink"  data-toggle="modal" data-target="#New_Command"   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-file"></i>
                  <?php echo _('New')?>        </a>         
            </li>
        <?php }?>



    </ul>
    
 

      
</nav>
    
    
    <table class="table"  id="dtmf_table">
  <thead class="thead-dark">
    <tr>
      <th scope="col"><?php echo _('DTMF')?></th>
      <th scope="col"><?php echo _('Description')?></th>
      <th scope="col"><?php echo _('Category')?></th>
      <th scope="col"><?php echo _('Action')?></th>

    </tr>
  </thead>
  <tbody>


  </tbody>
</table>

<script type="text/javascript">
function  Create_dtmf()
{


    $.post( "admin/save_edit_page.php", $( "#New_DTMF_Form" ).serialize() )
    .done(function( data ) {
    	alert("<?php echo _('Command is created')?>");
    	get_table();
    	$( "#New_DTMF_Form" ).trigger("reset");
    
    
    
    
    });
		
	return false;
	
	
}
function get_table()
{
	 $("#dtmf_table tbody").html("");
	 $.get( "admin/get_dtmf_list.php?Station_Name=<?php echo  $station_data['Station_Name']; ?>", function( data ) {
		 $("#dtmf_table tbody").html("");
		 $("#dtmf_table tbody").html(data);
		 

		});

}

function  Remove_command(id)
{


    $.post( "admin/save_edit_page.php",{ Remove_DTMF: "1", DMF_ID: id } )
    .done(function( data ) {

    	get_table();
    

    });
		
	return false;
	
	
}




function update_commade(id,comand,dectiption,cat)
{
	$("#dtmf_id").val(id);
	$("#command_d").val((comand))
	$("#Command_descript_d").val((dectiption))
	$("#Category_p").val(cat)

	
	
}
function  Update_dtmf()
{


    $.post( "admin/save_edit_page.php", $( "#Update_dtmf_form_id" ).serialize() )
    .done(function( data ) {
    	alert("<?php echo _('Command is created')?>");
    	get_table();
    
    
    
    
    });
		
	return false;
	
	
}


</script>




   <div class="modal fade" id="New_Command" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><?php echo _('New DTMF Command ')?></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
       
      <div class="modal-body">
         
        <form onsubmit="return Create_dtmf()" id="New_DTMF_Form" action="#">
          <div class="form-group">
            <label for="command"><?php echo _('DTMF command')?></label>
            <input type="text" name="command" class="form-control" id="command"  placeholder="">
          </div>
           <div class="form-group">
            <label for="command"><?php echo _('Description')?></label>
            <input type="text" name="Desciption" class="form-control" id="command"  placeholder="">
          </div>
          
         <div class="form-group">
            <label for="Category"><?php echo _('Category')?></label>
             <select class="custom-select" id="Category" name="Category">
             
             <?php 
             
             
             $dtmf_array = DTMF_Catgory();
             
             
             foreach ($dtmf_array as $key => $value) 
             {
                 $val  = str_replace("driver/card/", "", $value);
                 $val  = str_replace(".php", "", $value);
                 
         
                 echo  '<option value="'.$key.'">'.$value.'</option>';
                 
                 
             }
             
             
             
             
             ?>
             
             </select>
          </div>
            
          
          <input type="hidden" name="Station_id" class="form-control" id="Station_id" value="<?php echo $station_data['Station_id'] ?>">
          <input type="hidden" name="Mew_DTNF" class="form-control" id="Mew_DTNF" value="1">
          <input type="hidden" name="Station_name" class="form-control" value="<?php echo $station_data['Station_Name'] ?>">

          
          <button type="submit" onclick="Create_dtmf()" class="btn btn-primary" data-dismiss="modal"><?php echo _('Create')?></button>
 	  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Close')?></button>
    </form>



        </div>

      </div>
      
    </div>   
    </div>







   <div class="modal fade" id="Update_command" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><?php echo _('Edit DTMF Command ')?></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body">
        
         <form onsubmit="return Update_dtmf()" id="Update_dtmf_form_id" action="#">
          <div class="form-group">
            <label for="command"><?php echo _('DTMF command')?></label>
            <input type="text" name="command" class="form-control" id="command_d"  placeholder="">
          </div>
           <div class="form-group">
            <label for="command"><?php echo _('Description')?></label>
            <input type="text" name="Desciption" class="form-control" id="Command_descript_d"  placeholder="">
          </div>
          
          <input type="hidden" name="dtmf_id" class="form-control" id="dtmf_id" value="">
          <input type="hidden" name="Update_DTMF" class="form-control" value="1">

         <div class="form-group">
            <label for="Category_p"><?php echo _('Category')?></label>
             <select class="custom-select" id="Category_p" name="Category">
             
             <?php 
             
             
             $dtmf_array = DTMF_Catgory();
             
             
             foreach ($dtmf_array as $key => $value) 
             {
                 $val  = str_replace("driver/card/", "", $value);
                 $val  = str_replace(".php", "", $value);
        
                 
                 echo  '<option value="'.$key.'">'.$value.'</option>';
                 
                 
             }
             
             
             
             
             ?>
             
             </select>
          </div>

  
          
          
          <button type="submit" class="btn btn-primary"><?php echo _('Update')?></button>
    </form> 



        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo  _('Close')?></button>
        </div>
      </div>
      
    </div>   
    </div>
    
    
    
 
    
    



    
</div>


</div>
  
  
  </div>
  
  <?php }else{
    include 'admin/login.php'; }?>

<script type="text/javascript">

var file_station = '<?php echo  $station_data['Station_Name']; ?>';


tinymce.init({
  selector: '#mytextarea',
  plugins: 'print preview paste importcss searchreplace autolink directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
  removed_menuitems: 'newdocument',
  height : "800",
  images_upload_url:'postAcceptor.php?station='+file_station,
  images_upload_base_path:  window.location.origin+'/'
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
	  height : "800",
	  images_upload_url: 'postAcceptor.php?station='+file_station,
	  images_upload_base_path: window.location.origin
	});

	$('#Hardware_area_Dialog').on('resized', function (event) {
	    var hgt= $('#Hardware_area_Dialog').jqxWindow("height");
	    //alert("window:resized. height=" + hgt);
	    tinymce.activeEditor.theme.resizeTo(null, hgt-150);
	});

	

</script>

</body>
</html>
