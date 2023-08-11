<?php
include "../config.php";
include '../function.php';
define_settings();
set_laguage();

mysqli_set_charset($link,"utf8");

include_once '../redis.php';

$link->set_charset("utf8");


$talkgroup_array= array();
$station_array= array();
$json_data ="";

function Get_station_from_json()
{
    global $serveradress;
    global $json_data;
    
    
    //$context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));
    $key ="svxportal_cahce_ctcss_maptable";
    
    if(Redis_key_exist_enable($key))
    {
        
        $json_data = Redis_Get_key($key);
    }
    else
    {
        
        $json_data = file_get_contents($serveradress,false,$context);
        
        if (defined('USE_REDIS'))
        {
            Redis_Set_Key_time($key,$json_data,60);
        }
        
    }
    
    
    //$json_data = file_get_contents($serveradress);
    $json_data = iconv("utf-8", "utf-8//ignore", $json_data);
    
    $json = json_decode($json_data);
    global $talkgroup_array;
    global $station_array;
    
    
    
    foreach($json->nodes as $st => $station)
    {
        


        if($station->hidden == false)
        {
         $station_array[$st] = $st;
        }
            
            
            
       
        
    }
    
}

if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 ){
    


Get_station_from_json();

/*

foreach ($station_array as $station => $array) 
{

    $found =0;
    foreach ($stations as $i => $st)
    {
        if(strpos($st, $station) !== false )
        {
            $found =1;
        }
        else
        {
  
        }
           
    }
    if($found ==0)
    {
        unset ($station_array[$station]);
    }
        
        
        
    
}
*/



$Reflektor_link =  mysqli_connect($reflektor_db_host, $reflektor_db_user, $reflektor_db_password , $reflektor_db_db);
mysqli_set_charset($Reflektor_link,"utf8");


$tg =$Reflektor_link->real_escape_string($_GET['TG']);



$result = mysqli_query($Reflektor_link, "SELECT  * FROM `ACL`  where TG ='$tg'  ");

$acl ="";
while($row = $result->fetch_assoc())
{
    $acl =  $row['regex'];
    
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
<link rel="icon" type="image/png" href="../tower.svg">
<link	href="https://fonts.googleapis.com/css?family=Architects+Daughter&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css.css">
<link href="../dist/skin/blue.monday/css/jplayer.blue.monday.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../lib/jquery.min.js"></script>
<script type="text/javascript" src="../dist/jplayer/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="../dist/add-on/jplayer.playlist.min.js"></script>
<link rel="stylesheet" href="../lib/css/bootstrap.min.css"
	integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
	crossorigin="anonymous">
<!-- Optional theme -->

<!-- Latest compiled and minified JavaScript -->

<script src="../lib/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="../lib/css/bootstrap.min.css">


<link rel="stylesheet" href="../jquery-ui.css">
<script src="../jquery-ui.js"></script>

<script
	src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js"></script>
<link rel="stylesheet"
	href="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/css/ol.css">
<!-- fontawesome.com iconpac -->
<link href="../fontawesome/css/all.css" rel="stylesheet">
<!--load all styles -->
<script src="../js/chart/Chart.min.js"></script>

<style type="text/css">
.hideMe {
    display: none;
}




</style>




   <header>
 <nav class="navbar   navbar-dark sidebar_collor">
 <div>
   <a class="navbar-brand" href="#">
    <img src="../loggo.png" alt="Logo" style="width:40px;">

  </a>

   <a class="navbar-brand wite_font" href="#">
   
     SVX Portal <?php echo _('Select map sations')?>
   </a>
   
   </div>

  <div class="topnav-right" >
  <a href=""" onclick="window.close()" class="btn btn-outline-success my-2 my-sm-0" id="menu-toggle"><?php echo _('Exit')?></a>
  </div>
</nav> 
    </header>
    <body>
    

<div class="container-fluid">
<div class="row"> &nbsp;</div>

        <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text" id="inputGroup-sizing-default"><?php echo _('Regular expression')?></span>
      </div>
      <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="regex" id="regex" value="<?php echo $acl?>" >
    </div>
        
    <div class="alert alert-primary" role="alert">
    
      <?php echo _('This is an example of an station block regex ^((?!SK0AA).)*$')?> <br />
        <?php echo _('This is an example of an multistation allow regex  \b(?:SK0AA|SK1AA|SK2AB)\b')?> <br />
     
    
    </div>


<br />



<h3><?php echo _('Select station to generate an allow regex')?></h3>

    <form action="#" method="" enctype="multipart/form-data" >


<fieldset id="checkboxes" class="border border-primary">

 <div class="row">
 
 <?php 
 $station_lenth = count($station_array);

 $collengt = (int)( $station_lenth /2);
 

 $i =0;
 ?>

<?php foreach ($station_array as $station => $array) {?>


<?php 
if($i == 0)
{
    echo "<div class=\"col\">";
}
 ?>



    <div class="form-check" id="box_<?php echo  $station?>">
      <input type="checkbox" id="<?php echo  $station?>" class="form-check-input" name="<?php echo  $station?>" onchange="Generate_regex()">
      <label for="<?php echo  $station?>"  class="form-check-label"><?php echo  $station?></label>
    </div>


<?php 
$i++;
if($i == $collengt)
{
    echo "</div>";
    
    $i =0;
}

 ?>
 
 

    
    <?php }?>
    
</div>
</fieldset>

<br />




    <button id="sendMessage" type="button"  class="btn btn-primary" onclick="test_regex()"><?php echo _('Test Regex')?></button>
 
    <button id="sendMessage" type="button" onclick="save_to_db()"  class="btn btn-primary"><?php echo _('Save')?></button>


      
    </form>
    
    
    
    
</div>

</body>
</html>







<script>
  var button = document.querySelector("#sendMessage");


function Generate_regex()
{
		

	var regex_stations ="\\b(?:"
	  var selected = [];
	  $('#checkboxes input:checked').each(function() {
	      selected.push($(this).attr('name'));
	  });

	  console.log(selected);

        var i=0
		for(var k in selected){

		   if(i==0)
   		    regex_stations= regex_stations +selected[k];
		   else
			   regex_stations= regex_stations +'|'+selected[k];

		   i=i +1;
		}

        regex_stations= regex_stations+')\\b';

        
        $('#regex').val(regex_stations);
		


	  
	
}
function test_regex()
{

	
	const regex = new RegExp($('#regex').val());


	  var selected = [];
	  $('#checkboxes input').each(function() {
	      selected.push($(this).attr('name'));
	  });


	  

	  
		for(var k in selected){


			if(regex.test(selected[k]) == true)
			{
				$('#box_'+selected[k]).removeClass('bg-success bg-danger').addClass('bg-success');

			}
			else
			{
				$('#box_'+selected[k]).removeClass('bg-success bg-danger').addClass('bg-danger');
			}

		}


		
	
	

	}

function save_to_db()
{



	var rexgex_d =$('#regex').val();  

	$.post( "reflektor_action.php", { tg: "<?php echo $_GET['TG']?>", acl_tg: "1", regex : rexgex_d })
	  .done(function( data ) {



			const regex = new RegExp($('#regex').val());


			  var selected = [];
			  $('#checkboxes input').each(function() {
			      selected.push($(this).attr('name'));
			  });


			  

			  
				for(var k in selected){


					if(regex.test(selected[k]) == false)
					{
						$.post( "reflektor_action.php", { Kick_user: '1', call: selected[k] });

					}
	

				}
				alert("<?php echo _('Talkgroup settings are saved!')?>")
				window.close();

  
	   
	  });


	  
	
}
  
  
  
  button.addEventListener("click", function () {


	  

   
  });
</script>

<?php }?>