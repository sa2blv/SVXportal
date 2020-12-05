<?php
include "config.php";
include 'function.php';
define_settings();
set_laguage();

mysqli_set_charset($link,"utf8");

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
<?php if( $_SESSION['loginid'] >0 ){?>


function generate_password(field)
{
	var pwdChars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	var pwdLen = 12;
	var randPassword = Array(pwdLen).fill(pwdChars).map(function(x) { return x[Math.floor(Math.random() * x.length)] }).join('');


	$('#'+field).prop('type', 'text');
	$("#"+field).val(randPassword);
}

function validate_json_form()
{
	var detect_false =0;

	$("#valdate_form_json").each(function(){
	    var elements = $(this).find(':input') //<-- Should return all input elements in that specific form.
	    $(elements).removeClass("important bg-danger");

	});

	var CTCSS_TO_TG ="";
	var CTCSS_FQ="";


	var chk_arr =  document.getElementsByName("toneToTalkgroup[]");
	var chklength = chk_arr.length;             

	for(k=0;k< chklength;k++)
	{

		if(chk_arr[k].checked  == true) 
		{


		

			var ctcss_name = chk_arr[k].value;
			ctcss_name = ctcss_name.replace(".", "_"); 
			
			

			CTCSS_TO_TG = CTCSS_TO_TG+ $("#chnb_"+ctcss_name).val()+":"+$("#toneToTalkgroup_"+ctcss_name).val()+",";
			CTCSS_FQ= CTCSS_FQ +$("#chnb_"+ctcss_name).val()+",";
			
		 if($("#toneToTalkgroup_"+ctcss_name).val() == "")
		 {
			 $("#toneToTalkgroup_"+ctcss_name).addClass("important bg-danger");
			 detect_false++;
		 }	

		}
	    
	} 
	CTCSS_TO_TG  = CTCSS_TO_TG.substring(0, CTCSS_TO_TG.length - 1);
	CTCSS_FQ = CTCSS_FQ.substring(0, CTCSS_FQ.length - 1);

	 var Inputs_to_validate = ["nodeLocation","name", "lat", "long", "Locator", "Txl", "RxL", "Adir", "anth", "ant_comment","RX_freq","TX_freq","sysop","power"]; 

	 for (i = 0; i < Inputs_to_validate.length; i++) 
	{
		if($("#"+Inputs_to_validate[i]).val() == "")
		{
			$("#"+Inputs_to_validate[i]).addClass("important bg-danger");
			detect_false++
			//return false;
		}
	
	}

	 console.log(CTCSS_TO_TG);
	if(detect_false > 0)
	{
		return false;
	}	

	$("#CTCSS_TO_TG").val("CTCSS_TO_TG="+CTCSS_TO_TG);
	$("#CTCSS_FQ").val("CTCSS_FQ="+CTCSS_FQ);

	
return true;
	
}


		var Talkgrouparray = [];
<?php
        $result = mysqli_query($link, "SELECT * FROM `Talkgroup` ");
        
      
        


        $a =0;
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{

		    echo 'Talkgrouparray['.$a.'] = \''.$row['TG']."'; ";
		    $a++;
		}
		?>	

function update_select(id)
{
	$( "#chnb_"+id ).prop( "checked", true );
}


function CopyInput(input) 
{

	  /* Get the text field */
	  var copyText = document.getElementById(input);

	  /* Select the text field */
	  copyText.select();
	  copyText.setSelectionRange(0, 99999); /*For mobile devices*/

	  /* Copy the text inside the text field */
	  document.execCommand("copy");

	  /* Alert the copied text */
	  alert("Copied the text: " + copyText.value);
	  
} 

function check_register_user(input) 
{
	$("#Request_user_create").each(function(){
	    var elements = $(this).find(':input') //<-- Should return all input elements in that specific form.
	    $(elements).removeClass("important bg-danger");

	});
	

	 var Inputs_to_validate = [ "exampleInputEmail1","Password","Callsign","Description"]; 
	 var detect_false=0;
	 for (i = 0; i < Inputs_to_validate.length; i++) 
	{
		if($("#"+Inputs_to_validate[i]).val() == "")
		{
			$("#"+Inputs_to_validate[i]).addClass("important bg-danger");
			detect_false++
			//return false;
		}
	
	}


	 if(detect_false > 0)
	 {
		 return false;
	 }

	 var user_v = $("#Callsign").val();
	 
	 $.post( "admin/Reflektor_user_request.php", { uservalidate: "1", user: user_v })
	  .done(function( data ) {

		  if(data ==user_v )
		  {
			  alert( "<?php echo _('Station already exist!')?>" );
			  return false;
		  }
		  else
		  {
				$.post( "admin/Reflektor_user_request.php", $( "#Request_user_create" ).serialize())
				  .done(function( data ) {
			    	    alert("<?php echo _('Thanks for your registration an admin will contact you soon')?>");
			    	    $('#Request_user_create').trigger("reset");
				  });;
		  }
		
	    
	  });
		



	  return false;


} 
function Generate_svxlinkconf()
{

	$.post( "admin/Reflektor_config_generate.php", $( "#Generate_svxlinkconf" ).serialize())
	  .done(function( data ) {
  	    $('#result_config').html(data);
	  });;

	  
	return false;
}


jQuery(function($) {

	  $( "#datepicker" ).datetimepicker({
	      dateFormat: 'yy-mm-dd',
	      timeFormat: 'HH:mm:ss',
	        onShow: function () {
	            this.setOptions({
	                minDate:$('#fdate').val()?$('#fdate').val():false,
	                minTime:$('#fdate').val()?$('#fdate').val():false
	            });
	        }                    
	  }).attr('readonly', 'readonly'); 


	  
});

function load_node(id)
{
	$('#Station_id').val(id);
	
}

function send_command_to_node()
{
	$.post( "admin/send_command.php", $( "#node_form_command" ).serialize() )
	.done(function( data ) {

	});

	return false;
}


    


<?php }?>



</script>

</head>

<body>
<?php if( $_SESSION['loginid'] >0 ){?>

    
<header>
     <nav class="navbar   navbar-dark sidebar_collor justify-content-between">
      
       
        <div>
   <a class="navbar-brand" href="#">
    <img src="loggo.png" alt="Logo" style="width:40px;">

  </a>

   <a class="navbar-brand wite_font" href="#">
   
     SVX Portal <?php echo _('Network acsess')?>
   </a>
   
   </div>
   
   
   
   
   

      <a href="index.php" onclick="" class="btn btn-outline-success my-2 my-sm-0" id="menu-toggle"><?php echo _('Back') ?></a>
      
    </nav> 
</header>
        
        
        
    
 <div class="container-fluid">
 
 <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo _('My Stations')?></a>
  </li>
    <li class="nav-item">
    <a class="nav-link " id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="Talkgroup" aria-selected="true"><?php echo _('Request repeater Login')?></a>
  </li>
  
   <li class="nav-item">
    <a class="nav-link" id="node-tab" data-toggle="tab" href="#node" onclick="setTimeout(function(){map.updateSize();map.render();},300); " role="tab" aria-controls="log" aria-selected="false"><?php echo _('Generate node_info.json') ?></a>
  
  </li>
  
     <li class="nav-item">
    	<a class="nav-link" id="config-tab" data-toggle="tab" href="#config" onclick="" role="tab" aria-controls="log" aria-selected="false"><?php echo _('Generate svx.config parameters')?></a>
  
  </li>
  

</ul>


 
 <div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">



<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col"><?php echo _('Callsign')?></th>
      <th scope="col"><?php echo _('Lastseen')?></th>
      <th scope="col"><?php echo _('Send Command')?></th>

    </tr>
  </thead>
  <tbody>

<?php
$user_id= $_SESSION['loginid'];
$result = mysqli_query($link, "SELECT * FROM User_Premission LEFT JOIN RefletorStations ON RefletorStations.ID = User_Premission.Station_id 
LEFT JOIN Infotmation_page on Infotmation_page.Station_id = User_Premission.Station_id 
WHERE User_Premission.User_id ='$user_id' ");




// Associative array
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {


   ?>
   
   <tr >
   <td>
    <?php echo $row['Callsign'];?>
   </td>
   <td>
   <?php echo $row['Last_Seen'];?>
   </td>
   
      <td>
   

   
   <?php if(check_premission_station_RW($row['ID'],$_SESSION['loginid'])>0){?>
   <a href="#" data-toggle="modal" data-target="#command_to_node" onclick="load_node('<?php echo $row['Station_id'];?>')" data-placement="bottom" ><i data-toggle="tooltip" title="<?php echo  _('Send DTMF to node')?>"  class="fas fa-hashtag"></i></a>
   

   <i class="fas fa-cogs" data-toggle="tooltip" title="<?php echo  _('Send Command to Nod')?>" ></i>
   <?php }?>
   
   <a href="edit_station.php?Station_idnr=<?php echo  $row['ID']?>"><i class="fas fa-file"  data-toggle="tooltip" title="<?php echo  _('Edit Repeater')?>" ></i></a>
   
   <?php 
   

   
   
   ?>
   
   <?php if( $row['Module'] != "")
   {?>
   <a href="station_dash.php?Station_idnr=<?php  echo  $row['Station_id']?>"><i class="fas fa-tachometer-alt"  data-toggle="tooltip" title="<?php echo  _('Station Dashboard')?>" ></i></a>
   <?php }?>
   
   

   
   
   </td>
   
   
   </tr>
    
<?php   
}
?>	

  </tbody>
</table>


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
<div class="tab-pane fade show " id="login" role="tabpanel" aria-labelledby="login-tab">
    
    <h2><?php echo  _('User Credentals')?></h2>
    
    <form id="Request_user_create" action="#" method="post" onsubmit="return check_register_user()">
      <div class="form-group">
        <label for="exampleInputEmail1"><?php echo _('Email address')?></label>
        <div class="row">
            <div class="col-sm-8">
            <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="<?php echo _('Enter email')?>" >
        	</div>
    	</div>
      </div>
      
      <div class="form-group">
        <label for="Password"><?php echo _('Password')?></label>
       <div class="row">
       <div class="col-sm-8">
        <input type="password" name="Password" class="form-control" id="Password" placeholder="<?php echo _('Password')?>">
        </div>
         <div class="col-sm-4">
         	<button type="button" onclick="generate_password('Password')" class="btn btn-primary"><?php echo _('Generate password')?></button>
         </div>
      </div>
      
      
      <div class="form-group">
        <label for="exampleInputPassword1"><?php echo _('Callsign')?></label>
        <div class="row">
            <div class="col-sm-8">
            	<input type="text" class="form-control" name="Callsign" id="Callsign" placeholder="Sk0YT/R">
            </div>
        </div>
        
      </div>
      
    <div class="form-group">
        <label for="exampleInputPassword1"><?php echo _('Description')?></label>
         <div class="row">
       		 <div class="col-sm-8">
            	<input type="text" class="form-control" name="Description" id="Description" placeholder="<?php echo _('Description')?>">
            </div>
        </div>
    </div>
    
    <input type="hidden" class="form-control" name="newuser" value="1">
    
     <button type="submit"  disabled="disabled" class="btn btn-primary"><?php echo _('Register User')?></button>
</form>


</div>
</div>
  <div class="tab-pane fade show " id="node" role="tabpanel" aria-labelledby="node-tab">
  
      
    <form onsubmit="return validate_json_form()" id="valdate_form_json" method="post" action="generate_Node_json.php" target="_blank">
    
       <h2><?php echo  _('Repater information')?></h2>
      
    <div class="form-group">
        <label for="nodeLocation"><?php echo _('Station location ( Area )')?></label>
         <div class="row">
       		 <div class="col-sm-8">
            	<input type="text" class="form-control" name="nodeLocation" id="nodeLocation" placeholder="<?php echo _('Node location')?>"  >
            </div>
        </div>
    </div>
    
    
     <div class="form-group">
        <label for="sysop"><?php echo _('System operator')?></label>
         <div class="row">
       		 <div class="col-sm-8">
            	<input type="text" class="form-control" name="sysop" id="sysop" placeholder="SK0TM" >
            </div>
        </div>
    </div>   
    
    
    
    
    
    <!-- toneToTalkgroup -->
    
    <?php 
    $ctcss_array=ctcss_array();
    
    
    ?>
    
    <h2><?php echo  _('Tone To Talkgroup Mapping')?></h2>
    
    <div class="form-group">
    <table class="table col-sm-8">
      <thead class="table-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col"><?php echo _('CTCSS')?></th>
          <th scope="col"><?php echo _('TG#')?></th>
    
        </tr>
      </thead>
      <tbody>
      
    
    <?php 
    
    foreach ($ctcss_array as $ctcss) {
        
        $ctcss_id =  str_replace(".","_",$ctcss);
    
    
    ?>
    <tr>
    <td><input name="toneToTalkgroup[]" id="chnb_<?php echo $ctcss_id?>" type="checkbox" value="<?php echo $ctcss?>"/></td>
    <td><?php echo number_format($ctcss, 1, '.', '');?></td>
    <td>
    <div class="form-group">
    <input type="number" min="0" onchange="update_select('<?php echo $ctcss_id?>')" class="form-control" name="toneToTalkgroup_<?php echo $ctcss_id?>" id="toneToTalkgroup_<?php echo $ctcss_id?>" placeholder="<?php echo _('#TG')?>">
    </div>
    </td>
    
    
    </tr>
        
        <?php }?>
    
    
    
    
    


    
    
     </tbody>
    </table>
    
    <script type="text/javascript">
    $( document ).ready(function() {
    	<?php    
    	
    	foreach ($ctcss_array as $ctcss)
    	{
    	    $ctcss_id =  str_replace(".","_",$ctcss);
    	?>
    
        $( "#toneToTalkgroup_<?php echo $ctcss_id?>" ).autocomplete({
            source: Talkgrouparray
          });
    	<?php 
        }?>
        generate_coulor();
    });

        


    </script>
    
    <h2><?php echo  _('QTH Settings')?></h2>
    
    
    <div class="col-sm-8">
      <div id="map" class="map"></div>
      
      </div>
    
    
    
        <script type="text/javascript">
        
    
        var layer = new ol.layer.Tile({
          source: new ol.source.OSM()
        });
    
        var center = ol.proj.transform([-1.812, 52.443], 'EPSG:4326', 'EPSG:3857');
    
        var overlay = new ol.Overlay({
          element: document.getElementById('overlay'),
          positioning: 'bottom-center'
        });
    
        var view = new ol.View({
          center: center,
          zoom: 6
        });
    
    	var vectorSource = new ol.source.Vector({
          
        });
    	var Barsource = new ol.source.Vector({
          
        });
    	
    	
      var vectorLayer = new ol.layer.Vector({
            source: vectorSource
        });
    	
      var Barlayaer = new ol.layer.Vector({
            source: Barsource
        });
    
    vectorLayer.setZIndex(parseInt(5, 10) || 0);   
    Barlayaer.setZIndex(parseInt(5, 10) || 0);   
    
    
    var ico = [
    	"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAStSURBVGhD7VlNbBNHFA4HekQgtUdAgmRtovCjBFBROVS9tBVCIBUubREguKJeuPHXqpU4VG1pK7U4a8chRUBD2Fk7zo8gFIQh4k8KhNImggYS56cJaiAJFGhph/c2b1cz6yGyya5tJH/SJ9l+7833zfrtzHhdUkQRReQWPyxOzNE14+Owxr7XAybTNbPdIrzGz8IB8yPMofTCg67F3gprZiuY/RfM8impmf9gbnUw9iaV5x/hRQ3z9QAzlIYzINbWLGRzabj8IKIZq6FlRlQGs+QwfqM0bG4RDsQ2Qu8/VZh6SbIn1QFzAw2fG+hlxkq4Fx6pDU2Dk2MuJxl/ESn9+Q0QS0kGgHUrmqT3mfAFNamQFn+d5PwDtNN3bvGW7e18tHuMG+vPSJ9PRczFmpZt7WkxXWMHSM4fHCw1FlpLpyB6Ys1pfr9nnE8MPOIj10f5kdWtkikVMQdzsQZrG95vk+J476EWyXoPvFKiYKQ8xgcujFiGrIl0jvLj78mmVDz2zkl+79f7Tl3/+WEeWRSTcmA1/IZkvQdcqTuiWNuOS44ZvLKZTMKm+cFZPtb30KnHseQc1kOy3qK6jC2ThUx+p7XfMXJx/w0phqytbORNm5IW8bU7fi3U7dT/0dSfFoe9ZQnJewfr/CSI1C1v4uN0RcdTD/nhVc2SCWPtL1ar2UbxnsDPxBy8N+z4WN8EP1SZkOIRjW0jee8AX/VOUcRYd8Yx0Q/3iRhDYt/bcZup5LCcFzT5X10PnDhbJ08U9pUvSN47wES+FkVwybUN3I6nJAN1VQkn5ibGxFxxws1bL0gxmMi3JO8dYCKfiSKJD885Bu62DUoGamA1e0BLskj8DGNi7tDle068EcYUY3Cg/JTkvQMsh1tFEVyhbAPYHrgUi/ErB35z4javfHVTyokubeRjvRNOvP7dU1JcD8Y2k7x3sDZDQQSNi/3tvpoYT+7u4HdPDVhM7upIm6zYnrivuPeSSFnDApL3FtCzt0ShG9HbjpFeaC+30amIuX1n/3TqOyO3pDhsvl0k6z3geLJPFMP2wqXXNtPxY5dkZipe/vKmU4fLeFpbaWwPyXoP/KpB5D9RUNzUkM1bXCuPgrhBijXXDnbLOfBzOVQen0ey/gBWL1MUjS5p5L2nBy1Dvx/tsfYGMa4k5GAu1mBLRivirhxWT3L+wXrIIIma1q6OPV4Lk3LHXkTMxRr3icBi0FhFcv4CrlhLmrhHhL0jQTL+oyYYrwTB/1VGpkMcEw+nJJMbwA15XGVmmjxGw+cOkVKzHHb7ZwozL8fJB3sBGj63AHF8BKo2li39OCBmiuh8NhtMDKeZypKw+Q39VNo8i4bND/BgpzKXFTW2iYbLH3gJnwH3SlJpMDOewzFouPyiJsAWux8TZUJ87IO1NExhwP3DKxPCRPZSeeGgvrz+NTDWqTKsIuaGqkIzqbywgA+3M9pbYM/Qg6yKygoT0GL7leYFwnL7OaUXLrDFwGyH27xDzbyKOZRe2AgHT1TAAfCxexLQdn/j0YbSXg2A8U/cE4G220HhVweTGyVL2JPA1wWz8WUL/OcJbuwBZE7+hfIT8E28jaS3RRRRRF5QUvIc6Euk3wKNfCMAAAAASUVORK5CYII="
    	,
    	"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACIAAAAuCAMAAAB6WzuLAAADAFBMVEUReP4ReP4ReP4QeP4Pd/4Ndv4LdP4Jc/4Gcf4DcP4CcP4Bb/4Ab/4Ab/4Ab/4Abv4Abv4Abv4Abv4Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0AavYAY+sAXOAAWNgAVNEAUcwAT8kATscATcUATMQATMQAS8MAS8MAS8MAS8MAS8MAS8MAS8MAS8MAS8MATMMATMMATMIATcIAUMABVb4BWLwBW7oBXrcCXrICWqsBU6ABSpQBQ4sBQokBQYgCQIcCP4UEPIIFOH0JMnMLLW0NL28PMnIUNnUZOnghQHwnRYAqSIIuS4QyToc2U4o+W5BEYpZKaZpPbp5WdKJceqZng6xvi7BwjbByjrB3kbGBl7KGmbKKmrOMm7ONm7OOm7SPnLWQnbWRnraVormZp7ydrL+hssOnuciqvcuwwc+3x9S/zdrF09/J1uLL1+PN2eTR2+bV3ejZ3+nd4evg4+vj5Ozl5u3l5+3m6e/k6/Dh7vLZ8PTP9Pe7+fu2+vy1+/21+/21+/21+/21+/21+/21+/23+/26+/2//P3H/P3b/f32/v3+//3///z///z///z///v///z///3///7///7////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////1DM8mAAABAHRSTlP/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////AAAAAAAAAAAAAAAA//////////////////8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAApx8BhgAAAAlwSFlzAAAOwwAADsMBx2+oZAAAAVxJREFUOI2F1L2KwzAMAGA/wC2d825+gI5atIUrNORM4Qqe0rvFlGRyZo/lIA/iV7n8R5adVFvgQ7YsKcK/DUE/7EN/qVJV9Q5pqwuCHAIgV3WCaJQyW0OiahgxBWRhSNQBqVBmUYAi5IEx2MxAajwnSQZ6ISpxyhRoJ/LLb0rurCZypUmEc46lEb6mST5cSIY0oqM3OTlGsnzIctnKGXOEBBsvXtubOBcTeHhhgYuASO2FeUMUI+IUkZsXDSFzSTxLG7QwIvDTF50fE9uToIkR+RyeroEDAvexjfkRaUdSwS6R5TQMtCZG4DlP3bfcIWOSkZBOhgTMugEa0kSRPbomCdZ01VITLumqpfcEW0o6G+9jP2/hn+EerX3RMfLHj0IT/V/YYg/944RVVfgE8YoYfCYJqRy0TxOzVL48WkzWyrdqIjL3E/Rrn/ib5MdExJfyXNpjYnVh/DFJxD+xZNmYMqLlMgAAAABJRU5ErkJggg=="
    	,
    	"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACIAAAAuCAMAAAB6WzuLAAADAFBMVEUSMm4SMW0SMGwQNHMOO30LPoIIP4UGQIYHQIcGQosGRpYFSqEET68EULcEUboDULwCT74BTcEATMIAS8MAS8MAS8MATMQATMQATsYAUMgAU8wAWNIAYt0AbeoAc/IAefgAff4Afv4Afv4Bf/4Aff4Ae/4Aef4Ad/4Adf4Ac/4Acf4Ab/4Abv4Abv4Abv4Abv4Abv4Abv4Abv4Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0DbvwGbvsIbvkLbvgNb/cQb/YUb/UXb/Mab/Idb/Efb/Aib+8kb+4mcO0ocOwpcewqcesrcesrcessceotceoucekucekvcekvcegwcegxceYzcuQ1cuE3c985c91EdtJKeMxOechResVXfMFcfr1hgbpohLdrhbRshrRuh7NviLRwiLNwiLNxibN1i7R8jraDkbeHk7iKlbmLlbmNl7qOmLqPmbqRnLuTn7uVo7yYqLyaqr2crL6fr7+issCltcKnt8Osu8ewvsm2w868x9LBzNfFz9rI0dzK09/M1eHO2OTP2+fQ3OnQ3enP3urN4OzK4u/F5/O+7fm58f238/+38/+38/+38/+38/+38/+38/+38/+48//C8fzM8PnY7/bj7vPp7fLu7fDx7fDy7vH08PL28/X39fb6+Pj6+fn7+vr9/Pv+/vz///z///z///z///v///z9//38//78//7+//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////9YAKYnAAABAHRSTlP/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////AAD//////////////////////wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAxqLkTAAAAAlwSFlzAAAOwwAADsMBx2+oZAAAAXdJREFUOI2F1D1uwyAUAGCEZOkNzcYNcgOfghtlZ/HAirI4VVRVlRgsTz5B2DpZ8h04RwGHf+y+Lcqn9wM8I/1voPSHeoqBD3ycDogaPzAQGwAdnxpEYEJoCIL5XBDJgOZBsMjIiAmtAnhCnrgG0Vgy4UuTUBCe8EaVPbDayXfs9Lq4iD3znTCSAxO3NA3Sk0/SL0tlbBq08izJ7S19ms5m6fw4/q9rSvCs0ebPpPcF+pTAp0avOE9qw0xCI1mRrJDptyZ5u2TQaC5JdXhI4YYIR0fhyw59JigoQ7JLLAVF9ujSZrJhXJKHu8buWFDYHBnhUJiRHfEz9WUfJol8v7o7ia36iEkc2W+yrwn8hA0QQNM3FwhP9ojRVuApXTVoCJKuWntP3IYEsr7qfTTvLf8yPMpShK0F+S1LYVl9X4rFtvdXkmIqphtEc2iVyT+HsR0Quk2kn9wfWk3C5GmZguz3acpsx0QPpCxTEWMugzonSjCpz0kj/gCNqVLifMYqzwAAAABJRU5ErkJggg=="
    	,
    	"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACIAAAAuCAMAAAB6WzuLAAADAFBMVEUTMWsRLGoONHANPn8GQIUKQIcMQ4oNSZMLT54KVaoIWbgGWLsDU74CTsEBTMMATMQATsgAUs4AVtUAYukAbPoAbv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0EbvYIbfAMbesPbOcXbuAncNE4dMJCdrlLeLNReq9WfK5cf61jg65phatthqluh6xvi7JwjLJxjbJ2kLJ6lLKAl7KGmrKKnLKMnLOOm7OPm7OPm7SQm7WUnreXormaprudrb+fuMmgxdOfyNmezd+c0+Sa2OmZ2eqZ2eqZ2eqZ2eqZ2eqZ2eqZ2eqZ2eqZ2eqZ2eqa2ema2Oie1+Si1uKq1N6w092z0ty40du90dvB0tzF093J097L1eDO1uHR1+TU2ubX3enY3urZ3+rf4evj5O3m5u3p5+7s6u7v7PDx7fDy7vDz8PD18/P49vb7+vj8+/n9/fn9/fn+/vn+/vn///n///j///j///f///j///v///z///7////////////////9///8///+//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////92dZ6MAAABAHRSTlP///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQAdNaAAAAAlwSFlzAAAOwwAADsMBx2+oZAAAAVxJREFUOI2F1L1uhDAMAOBYWFXFkLfhVTJRZekNWWDjCbocC+pwYmBn6cKr+I0KgYDzx2U6dJ8cO44j6O0S/GNszZeudTNkyNR8AsptIRZ6SBADUlbnknChg/QKK39JMB5pwEXous4h1Iy0UF0iMhsZoEySCo0j+srTJxWMO/lBXzAj9U6UzBIbRtDAyo3IFkbMYSa+KbYoH2UkmIFfEgvEgCFsSbww8f+lpCHRY7CFb9Z8QxJ+yJrE8D7KBLe5bOlSkSzIlYTjSq6jS52LCBoQC3zYNhY3PULbRmpuLkO9X4YJsgSfx637zt06G8SSBTIE+3MCDKaJZnOkeJjzNwx81MJZtJnwUfPm5Fxi4mR+iUisDfRfhkc09moOyF+4FfTR+8IG227ziJ+goCpFCUKaGXgmCascDaVJ73rlDi0mZ+VXNRE5+olmyROqZbhNRFZT1uM9GY3q6Z4k1j81qd3gAk1yDQAAAABJRU5ErkJggg=="
    	,
    	"data:image/png;base64,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/GMzLdsAADwCaVRYdFhNTDpjb20uYWRvYmUueG1wAAAAAAA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/Pgo8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjYtYzA2NyA3OS4xNTc3NDcsIDIwMTUvMDMvMzAtMjM6NDA6NDIgICAgICAgICI+CiAgIDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+CiAgICAgIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiCiAgICAgICAgICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgICAgICAgICAgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iCiAgICAgICAgICAgIHhtbG5zOnN0RXZ0PSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VFdmVudCMiCiAgICAgICAgICAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIKICAgICAgICAgICAgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIgogICAgICAgICAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgICAgICAgICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iPgogICAgICAgICA8eG1wOkNyZWF0b3JUb29sPkFkb2JlIFBob3Rvc2hvcCBFbGVtZW50cyAxNC4wIChXaW5kb3dzKTwveG1wOkNyZWF0b3JUb29sPgogICAgICAgICA8eG1wOkNyZWF0ZURhdGU+MjAxOS0wMS0yNlQxMzozMToxMi0wNTowMDwveG1wOkNyZWF0ZURhdGU+CiAgICAgICAgIDx4bXA6TWV0YWRhdGFEYXRlPjIwMTktMDEtMjZUMTQ6MDI6NDEtMDU6MDA8L3htcDpNZXRhZGF0YURhdGU+CiAgICAgICAgIDx4bXA6TW9kaWZ5RGF0ZT4yMDE5LTAxLTI2VDE0OjAyOjQxLTA1OjAwPC94bXA6TW9kaWZ5RGF0ZT4KICAgICAgICAgPHhtcE1NOkluc3RhbmNlSUQ+eG1wLmlpZDo0ZjI2MzYxNy0wNDViLTEzNGMtOWJhMy0zZmM5NDlhNGE3NTA8L3htcE1NOkluc3RhbmNlSUQ+CiAgICAgICAgIDx4bXBNTTpEb2N1bWVudElEPmFkb2JlOmRvY2lkOnBob3Rvc2hvcDphNWRjOWQ4OS0yMTljLTExZTktYmVhOC1lNTA2MDlhNzA0NTg8L3htcE1NOkRvY3VtZW50SUQ+CiAgICAgICAgIDx4bXBNTTpPcmlnaW5hbERvY3VtZW50SUQ+eG1wLmRpZDphMzlhYjZkNC1mYzFlLTlhNDMtODU5Zi1hZDg3ZmU0ZDM1ZWQ8L3htcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD4KICAgICAgICAgPHhtcE1NOkhpc3Rvcnk+CiAgICAgICAgICAgIDxyZGY6U2VxPgogICAgICAgICAgICAgICA8cmRmOmxpIHJkZjpwYXJzZVR5cGU9IlJlc291cmNlIj4KICAgICAgICAgICAgICAgICAgPHN0RXZ0OmFjdGlvbj5jcmVhdGVkPC9zdEV2dDphY3Rpb24+CiAgICAgICAgICAgICAgICAgIDxzdEV2dDppbnN0YW5jZUlEPnhtcC5paWQ6YTM5YWI2ZDQtZmMxZS05YTQzLTg1OWYtYWQ4N2ZlNGQzNWVkPC9zdEV2dDppbnN0YW5jZUlEPgogICAgICAgICAgICAgICAgICA8c3RFdnQ6d2hlbj4yMDE5LTAxLTI2VDEzOjMxOjEyLTA1OjAwPC9zdEV2dDp3aGVuPgogICAgICAgICAgICAgICAgICA8c3RFdnQ6c29mdHdhcmVBZ2VudD5BZG9iZSBQaG90b3Nob3AgRWxlbWVudHMgMTQuMCAoV2luZG93cyk8L3N0RXZ0OnNvZnR3YXJlQWdlbnQ+CiAgICAgICAgICAgICAgIDwvcmRmOmxpPgogICAgICAgICAgICAgICA8cmRmOmxpIHJkZjpwYXJzZVR5cGU9IlJlc291cmNlIj4KICAgICAgICAgICAgICAgICAgPHN0RXZ0OmFjdGlvbj5zYXZlZDwvc3RFdnQ6YWN0aW9uPgogICAgICAgICAgICAgICAgICA8c3RFdnQ6aW5zdGFuY2VJRD54bXAuaWlkOjE0YjAzYzBhLWM4ZGYtMTg0Yi1hZmU0LTlhM2YxN2JhNzAxYjwvc3RFdnQ6aW5zdGFuY2VJRD4KICAgICAgICAgICAgICAgICAgPHN0RXZ0OndoZW4+MjAxOS0wMS0yNlQxMzozMToxMi0wNTowMDwvc3RFdnQ6d2hlbj4KICAgICAgICAgICAgICAgICAgPHN0RXZ0OnNvZnR3YXJlQWdlbnQ+QWRvYmUgUGhvdG9zaG9wIEVsZW1lbnRzIDE0LjAgKFdpbmRvd3MpPC9zdEV2dDpzb2Z0d2FyZUFnZW50PgogICAgICAgICAgICAgICAgICA8c3RFdnQ6Y2hhbmdlZD4vPC9zdEV2dDpjaGFuZ2VkPgogICAgICAgICAgICAgICA8L3JkZjpsaT4KICAgICAgICAgICAgICAgPHJkZjpsaSByZGY6cGFyc2VUeXBlPSJSZXNvdXJjZSI+CiAgICAgICAgICAgICAgICAgIDxzdEV2dDphY3Rpb24+c2F2ZWQ8L3N0RXZ0OmFjdGlvbj4KICAgICAgICAgICAgICAgICAgPHN0RXZ0Omluc3RhbmNlSUQ+eG1wLmlpZDo0ZjI2MzYxNy0wNDViLTEzNGMtOWJhMy0zZmM5NDlhNGE3NTA8L3N0RXZ0Omluc3RhbmNlSUQ+CiAgICAgICAgICAgICAgICAgIDxzdEV2dDp3aGVuPjIwMTktMDEtMjZUMTQ6MDI6NDEtMDU6MDA8L3N0RXZ0OndoZW4+CiAgICAgICAgICAgICAgICAgIDxzdEV2dDpzb2Z0d2FyZUFnZW50PkFkb2JlIFBob3Rvc2hvcCBFbGVtZW50cyAxNC4wIChXaW5kb3dzKTwvc3RFdnQ6c29mdHdhcmVBZ2VudD4KICAgICAgICAgICAgICAgICAgPHN0RXZ0OmNoYW5nZWQ+Lzwvc3RFdnQ6Y2hhbmdlZD4KICAgICAgICAgICAgICAgPC9yZGY6bGk+CiAgICAgICAgICAgIDwvcmRmOlNlcT4KICAgICAgICAgPC94bXBNTTpIaXN0b3J5PgogICAgICAgICA8ZGM6Zm9ybWF0PmltYWdlL3BuZzwvZGM6Zm9ybWF0PgogICAgICAgICA8cGhvdG9zaG9wOkNvbG9yTW9kZT4zPC9waG90b3Nob3A6Q29sb3JNb2RlPgogICAgICAgICA8cGhvdG9zaG9wOklDQ1Byb2ZpbGU+c1JHQiBJRUM2MTk2Ni0yLjE8L3Bob3Rvc2hvcDpJQ0NQcm9maWxlPgogICAgICAgICA8dGlmZjpPcmllbnRhdGlvbj4xPC90aWZmOk9yaWVudGF0aW9uPgogICAgICAgICA8dGlmZjpYUmVzb2x1dGlvbj45NjAwMDAvMTAwMDA8L3RpZmY6WFJlc29sdXRpb24+CiAgICAgICAgIDx0aWZmOllSZXNvbHV0aW9uPjk2MDAwMC8xMDAwMDwvdGlmZjpZUmVzb2x1dGlvbj4KICAgICAgICAgPHRpZmY6UmVzb2x1dGlvblVuaXQ+MjwvdGlmZjpSZXNvbHV0aW9uVW5pdD4KICAgICAgICAgPGV4aWY6Q29sb3JTcGFjZT4xPC9leGlmOkNvbG9yU3BhY2U+CiAgICAgICAgIDxleGlmOlBpeGVsWERpbWVuc2lvbj4zMjwvZXhpZjpQaXhlbFhEaW1lbnNpb24+CiAgICAgICAgIDxleGlmOlBpeGVsWURpbWVuc2lvbj4zMjwvZXhpZjpQaXhlbFlEaW1lbnNpb24+CiAgICAgIDwvcmRmOkRlc2NyaXB0aW9uPgogICA8L3JkZjpSREY+CjwveDp4bXBtZXRhPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgIAo8P3hwYWNrZXQgZW5kPSJ3Ij8+fDpo+wAAACBjSFJNAAB6JQAAgIMAAPn/AACA6QAAdTAAAOpgAAA6mAAAF2+SX8VGAAAAaElEQVR42mL8//8/w0ACJoYBBqMOGHXAqANGHcBCrMJORjGSiszy/68YqeoAqKHEOpb6IQAD72RV8coLPb49mghHHTDqgGFaEpKbz6nqAFJKOGIB42ireNQBow4YdcCIdwAAAAD//wMAW8MSs2NvdMsAAAAASUVORK5CYII="
    	];
    
    
    	function addmarker(iconindex,lat, lon)
    	{
    	        iconFeature = new ol.Feature({
    	            geometry: new ol.geom.Point(new ol.proj.transform([lon,lat], 'EPSG:4326', 'EPSG:3857')),
    	        });
    
    	        
    
    	        var iconStyle = new ol.style.Style({
    	                image: new ol.style.Icon(({
    	                anchor: [0.5, 1.0],
    	                anchorXUnits: 'fraction',
    	                anchorYUnits: 'fraction',
    	                opacity: 0.9,
    	                src: ico[iconindex]
    	                })),
    	                text: new ol.style.Text({
    	                             font: '12px helvetica,sans-serif',
    	                             text: "<?php echo _('Station')?>",
    	                             fontSize: 12,
    	                             fill: new ol.style.Fill({
    	                                 color: '#000'
    	                             }),
    	                             stroke: new ol.style.Stroke({
    	                                 color: '#fff',
    	                                 width: 2
    	                             })
    	                          })
    	        });
    			
    	        iconFeature.setStyle(iconStyle);
    	        iconFeature.setId("icon");
    			vectorSource.addFeature(iconFeature);
    	        
    	        return vectorLayer;
    	}
    
    
    
    
    	
    
    
        var map = new ol.Map({
          target: 'map',
          layers: [layer,vectorLayer,Barlayaer],
          view: view
        })
    
        map.on('click', function (evt) {
            var coord = map.getCoordinateFromPixel(evt.pixel);
            var lonlat = ol.proj.transform(coord, 'EPSG:3857', 'EPSG:4326');
            console.log(lonlat);
            vectorSource.clear();
    
            addmarker(0,lonlat[1], lonlat[0]);
    
            $("#lat").val(lonlat[1]);
            $("#long").val(lonlat[0]);
    
            
         });
    
    
        
    	
        
        </script>
        
        <div class="form-group">
        <label for="nodeLocation"><?php echo _('Location name')?></label>
         <div class="row">
       		 <div class="col-sm-8">
            	<input type="text" class="form-control" name="name" id="name" placeholder="<?php echo _('Stockholm')?>">
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label for="lat"><?php echo _('Latitude')?></label>
         <div class="row">
       		 <div class="col-sm-8">
            	<input type="text" class="form-control" name="lat" id="lat" placeholder="0.00">
            </div>
        </div>
    </div>
    
    
    
    
    <div class="form-group">
        <label for="long"><?php echo _('Longitude')?></label>
         <div class="row">
       		 <div class="col-sm-8">
            	<input type="text" class="form-control" name="long" id="long" placeholder="0.00">
            </div>
        </div>
    </div>
    
    
    <div class="form-group">
        <label for="Locator"><?php echo _('Locator')?></label>
         <div class="row">
       		 <div class="col-sm-8">
            	<input type="text" class="form-control" name="Locator" id="Locator" placeholder="KO03DU">
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label for="Txl"><?php echo _('Tx Letter "Get From svxlink.conf"')?></label>
         <div class="row">
       		 <div class="col-sm-8">
            	<input type="text" class="form-control" name="Txl" id="Txl" placeholder="A">
            </div>
        </div>
    </div>
    
    
    <div class="form-group">
        <label for="RxL"><?php echo _('RX letter "Get From svxlink.conf"')?></label>
         <div class="row">
       		 <div class="col-sm-8">
            	<input type="text" class="form-control" name="RxL" id="RxL" placeholder="A">
            </div>
        </div>
    </div>
    
    
  	<div class="form-group">
        <label for="RxL"><?php echo _('Sql type')?></label>
         <div class="row">
       		 <div class="col-sm-8">

                <select name="SQL_TYPE[]" id="cars" name="SQLtype"  class="form-control form-control-lg" multiple>
                  <option value="CTCSS" selected="selected">CTCSS</option>
                  <option value="COR">COR</option>
                  <option value="VOX">VOX</option>
                </select>
            </div>
        </div>
    </div>  
    
          <div class="form-group">
        <label for="power"><?php echo _('Transmitter Power w')?></label>
         <div class="row">
       		 <div class="col-sm-8">
            	<input type="text" class="form-control" name="power" id="power" placeholder="200">
            </div>
        </div>
    </div>

  
      <div class="form-group">
        <label for="Adir"><?php echo _('Antenna Directon')?></label>
         <div class="row">
       		 <div class="col-sm-8">
            	<input type="text" class="form-control" name="Adir" id="Adir" placeholder="200">
            </div>
        </div>
    </div>
    
    
    
    <div class="form-group">
        <label for="anth"><?php echo _('Antenna Height')?></label>
         <div class="row">
       		 <div class="col-sm-8">
            	<input type="text" class="form-control" name="anth" id="anth" placeholder="0 m">
            </div>
        </div>
    </div>
 
 
     <div class="form-group">
        <label for="ant_comment"><?php echo _('Antenna Description')?></label>
         <div class="row">
       		 <div class="col-sm-8">
            	<input type="text" class="form-control" name="ant_comment" id="ant_comment" placeholder="4 ELEMENT STACKED">
            </div>
        </div>
    </div>   
      
     <div class="form-group">
        <label for="TX_freq"><?php echo _('TX Frequency')?></label>
         <div class="row">
       		 <div class="col-sm-8">
            	<input type="text" class="form-control" name="TX_freq" id="TX_freq" placeholder="145.6250">
            </div>
        </div>
    </div>   
    
      <div class="form-group">
        <label for="RX_freq"><?php echo _('RX Frequency')?></label>
         <div class="row">
       		 <div class="col-sm-8">
            	<input type="text" class="form-control" name="RX_freq" id="RX_freq" placeholder="145.0250">
            </div>
        </div>
    </div>   
    
    <div>
    
  
    
    </div>
    
        <h3><?php echo  _('Parameters dor Svxlink.conf')?></h3>
<div class="card col-sm-8">
  <div class="card-body">

			<label for="CTCSS_TO_TG">CTCSS_TO_TG <?php echo _('Parameter')?></label>
             <div class="row">
           		 <div class="col-sm-8">
                  	<input type="text" class="form-control" name="CTCSS_TO_TG" id="CTCSS_TO_TG" disabled="disabled">
                  </div>
                  <div class="col-sm-4">

              	  </div>
                  
              </div>
              <label for="CTCSS_FQ">CTCSS_FQ <?php echo _('Parameter')?></label>
             <div class="row">
           		 <div class="col-sm-8">
           		 
           		 
              		<input type="text" class="form-control" name="CTCSS_FQ" id="CTCSS_FQ" disabled="disabled"> 
              </div>
              <div class="col-sm-4">
				
				
				
              </div>
              
              
              
          </div>
          
          
          
  </div>
</div>
          

          
  
    

    
    


    
    
       
    
      <button type="submit" class="btn btn-primary"><?php echo _('Generate JSON / Paramters')?></button>
</form>
    
         
         
        
        
    
    
    
    
    
    
    
    </div>
</div>
  <div class="tab-pane fade show " id="config" role="tabpanel" aria-labelledby="config-tab">
  
  
      <form id="Generate_svxlinkconf" action="#" method="post" onsubmit="return Generate_svxlinkconf()">
      
      <div class="form-group">
        <label for="Callsign1a"><?php echo _('Callsign')?></label>
        <div class="row">
            <div class="col-sm-8">
            	<input type="text" name="Callsign1a" class="form-control" id="Callsign1a" aria-describedby="emailHelp" placeholder="SK2AT" >
        	</div>
    	</div>
      </div>
      
     <div class="form-group">
        <label for="password1"><?php echo _('Password')?></label>
        <div class="row">
            <div class="col-sm-8">
            	<input type="password" name="password" class="form-control" id="password1" aria-describedby="emailHelp" placeholder="" >
        	</div>
    	</div>
      </div>
      
       <label for="selectpicker"><?php echo _('Select talkgroups to monitor')?></label>
       
       
  <?php 
	$result = mysqli_query($link, "SELECT * FROM `Talkgroup` ORDER BY `Talkgroup`.`TG` ASC");
	
	?>
       <div class="col-sm-8">
 	<select class="custom-select custom-select-sm" id="selectpicker" name="tg[]"  multiple="multiple" size="<?php echo  ($result->num_rows+3)?>" >
                                 <option value=""><?php echo _('No Talkgroup Filter')?></option>
                                  <optgroup label="<?php echo _('Talkgroup')?>">
                                  
                 <?php 
    			$result = mysqli_query($link, "SELECT * FROM `Talkgroup` ORDER BY `Talkgroup`.`TG` ASC");

    			// Numeric array

    			// Associative array
    			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    ?>
    <option value="<?php echo $row["TG"]?>" style="background-color: <?php echo $row["Collor"]?>"><?php echo $row["TG"] ?>		<?php echo  $row["TXT"] ?></option>        
    <?php }?>


                                  </optgroup>
                                </select>      
                                
           
     
      </div>
      
      <input type="submit" value="<?php echo _('Generate config')?>" />
      
      </form>
  
  <div id="result_config" class="card"></div>
   
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  </div>
  
  <?php }else{
    include 'admin/login.php'; }?>

</body>
</html>
