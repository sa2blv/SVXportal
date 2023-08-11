<?php
include "config.php";
include 'function.php';
include "Mqtt_driver.php";
define_settings();
set_laguage();

function getContrastColor($hexColor)
{
    // hexColor RGB
    $R1 = hexdec(substr($hexColor, 1, 2));
    $G1 = hexdec(substr($hexColor, 3, 2));
    $B1 = hexdec(substr($hexColor, 5, 2));
    
    // Black RGB
    $blackColor = "#000000";
    $R2BlackColor = hexdec(substr($blackColor, 1, 2));
    $G2BlackColor = hexdec(substr($blackColor, 3, 2));
    $B2BlackColor = hexdec(substr($blackColor, 5, 2));
    
    // Calc contrast ratio
    $L1 = 0.2126 * pow($R1 / 255, 2.2) +
    0.7152 * pow($G1 / 255, 2.2) +
    0.0722 * pow($B1 / 255, 2.2);
    
    $L2 = 0.2126 * pow($R2BlackColor / 255, 2.2) +
    0.7152 * pow($G2BlackColor / 255, 2.2) +
    0.0722 * pow($B2BlackColor / 255, 2.2);
    
    $contrastRatio = 0;
    if ($L1 > $L2) {
        $contrastRatio = (int)(($L1 + 0.05) / ($L2 + 0.05));
    } else {
        $contrastRatio = (int)(($L2 + 0.05) / ($L1 + 0.05));
    }
    
    // If contrast is more than 5, return black color
    if ($contrastRatio > 5) {
        return '#000000';
    } else {
        // if not, return white color.
        return '#FFFFFF';
    }
}









?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />

<meta name="viewport"
	content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title> SVX Portal <?php echo PORTAL_VERSION ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<meta charset="UTF-8">
<meta name="description" content="Svxportal For SvxReflektor">
<meta name="keywords" content="svxlink,svxreflektor,sa2blv">
<meta name="author" content="Peter SA2BLV">

<link rel="icon" type="image/png" href="tower.svg">
<link
	href="https://fonts.googleapis.com/css?family=Architects+Daughter&display=swap"
	rel="stylesheet">
<link rel="stylesheet" href="css.css">
<link href="dist/skin/blue.monday/css/jplayer.blue.monday.min.css"
	rel="stylesheet" type="text/css" />
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



<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>


<script type="text/javascript">

$( document ).ready(function() {
	add_node_collors();
	reflector_handler();
	MQTTconnect();
});


var node_collors = new Array();
function add_node_collors()
{
	<?php
$result = mysqli_query($link, "SELECT * FROM `RefletorStations` ");

// Numeric array

// Associative array
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    if ($row["Callsign"] == null) {
        $row["Callsign"] = "nodata";
    }
    ?>
        node_collors["<?php echo $row["Callsign"]?>"]= new Array();
        node_collors["<?php echo $row["Callsign"]?>"]["id"] ="<?php echo $row["Callsign"]?>";

        <?php

        $color_text = getContrastColor($row["Collor"]);
    
    ?>

	    

	    node_collors["<?php echo $row["Callsign"]?>"]["color"] = "<?php echo $row["Collor"]?>";   
        node_collors["<?php echo $row["Callsign"]?>"]["text"] = "<?php echo $color_text?>";      
            		          
    <?php }?>

}




var last_heard_active = 0;
function get_active_last()
{
	if (last_heard_active == 1)
	{
		get_tg('');
	}
	return;
}
function activate_last_heard()
{
	last_heard_active =1;
	get_tg('');
	return;
}

function create_bar_rssi(id)
{
    var canvas = document.getElementById(id);
    var context = canvas.getContext('2d');
    width= 0.2 *window.innerWidth
    var value_scale =width/100;
    canvas.setAttribute('width', width);

    canvas.setAttribute('height', 10);
    canvas.setAttribute('style', 'border:1px solid #000000;');


    context.stroke(); 
    context.fillRect(1, 1 , -0,1); 
    
}

function update_bar(id,value,k,collor)
{


		
    var canvas = document.getElementById(id);
    var context = canvas.getContext('2d');
    width= 0.35 *window.innerWidth
    var value_scale =width/100;
    canvas.setAttribute('width', width);

    canvas.setAttribute('height', 10);
    canvas.setAttribute('style', 'border:1px solid '+collor+';');

    
    context.stroke(); 
    context.fillRect(1, 1 , -0,1); 

    if( collor == "#FFFFFF")
    {
    	context.fillStyle ="#E0FFFF";
    }
    else
    {
    	context.fillStyle ="#1932F7";
    }
    
   	if(value>=0 && value <100)
   		context.fillRect(1, 1 , (value_scale*value)-3,8); 
	else if (value >=100)
		context.fillRect(1, 1 , (value_scale*100)-3,8);

    context.rect(10, 10, 150, 100);


}








old_get_tg_data ="";

function get_tg(tg)
{

	if(tg != 0)
	{


	$.get( "last_talker_api.php", { TG: tg } )
	  .done(function( data ) 
    {
		  const myArr = JSON.parse(data);

		  if(old_get_tg_data != data)
		  {
			  old_get_tg_data = data;
    		  $("#last_heard_table > tbody").html("");
    
    		  for(var k in myArr)
    	      {
    
    			  var box = "<div style=\"border:2px solid black; width: 25px; height :25px;  background-color:"+myArr[k].TG_collor+" \"></div>";
    			  var html_str = "<tr><td>"+myArr[k].Node+"</td><td>"+myArr[k].Location+"</td><td>"+box+"</td><td>"+myArr[k].TG+"</td><td>"+myArr[k].TG_Name+"</td><td class='text-nowrap'>"+myArr[k].Time+"</td><td>"+myArr[k].Duration+"</td></tr>";
    			  $("#last_heard_table > tbody").append(html_str);

    			  
    			  
    		  }			  
		  }

		  

<?php
/*
 *
 *
 * $previus_talker = $row["Callsign"];
 * $json_data[$val]["Node"] = $row["Callsign"];
 * $json_data[$val]["Time"] = $row["Time"];
 * $json_data[$val]["TG"] = $row["Talkgroup"];
 * $json_data[$val]["Duration"] = $row["Talktime"];
 * $json_data[$val]["Location"] = $station_array["Location"];
 * $json_data[$val]["TG_collor"] = $tg_array["Collor"];
 * $json_data[$val]["TG_Name"] = $tg_array["TXT"];
 *
 */

?>
		  	


			    
	  });
	}
	
}
var current_tg ='<?php echo $_GET['TG']?>';

function reflector_handler()
{
    $.getJSON( "<?php echo $serveradress ?>", function( data )
    	    {

    	  	  if (data === undefined) 
    	      {
    	  		interval = setTimeout(reflector_handler, 800);
    	  		console.log("No Json data!");
    	      }
    	  	  else
    	  	  {

        	  	  <?php
        	  	  if($_GET['follow_node'])
        	  	  {
        	  	  ?>
				if(current_tg != data.nodes['<?php echo $_GET['follow_node'];?>'].tg  );
				{
					get_tg(current_tg);
				}

        	  	current_tg = data.nodes['<?php echo $_GET['follow_node'];?>'].tg 
        	  	  



        	  	  
        	  	  <?php } ?>
        	  	      
        	  	      

    	  		 $("#station_data").html("<div style=\"height:150px !important;\"> </div>");
				 $("#station_data").css("background-color","white");
				 $("#station_data").css("color","black");

				 
    	  		for(var k in data.nodes)
        	  	{
            	  	if(data.nodes[k].tg == current_tg )
            	  	{
                	  	if(data.nodes[k].isTalker == true )
                	  	{
                    	
                    	if(data.nodes[k].nodeLocation == undefined)
                    	{
                    		data.nodes[k].nodeLocation =data.nodes[k].NodeLocation;
                    	}
                    	var node_location_name ="";
                    	var node_location_name_qth ="";
                    	var node_location_value =0;
                    	var node_location_freq ="";
                    	var Freqvensy
                    	

                        for(var qth in data.nodes[k].qth)
                        {
                     
                        	var counter_tbody =0;
                        	var counter_tbody_max =0;
                    		var qth_node_name =data.nodes[k].qth[qth].name

                        	for(var qth1 in data.nodes[k].qth[qth].rx)
                        	{

                        		var rx_active =data.nodes[k].qth[qth].rx[qth1].active;
                               	var qth_name =data.nodes[k].qth[qth].rx[qth1].name;
                            	var qth_name_location =data.nodes[k].qth[qth].name;

                            	if(rx_active == true)
                            	{
                            		node_location_name = data.nodes[k].qth[qth].rx[qth1].name;
                            		node_location_name_qth =data.nodes[k].qth[qth].name;

                            		Freqvensy =String(data.nodes[k].qth[qth].rx[qth1].freq);

                                   	Freqvensy = parseFloat(Freqvensy);
                                   	Freqvensy = Freqvensy.toFixed(4); 
                                   	
                            		
                            		
                            		node_location_value = data.nodes[k].qth[qth].rx[qth1].siglev;
                            		
                            	}

                   

                        		

                        	}

                        }

                    	
						var str ="<div style=\"height:150px !important;\">";
						str += "<table class=\"col-md-12\" id=\"table\" >"; 
						str += "<tr><th> "+"<?php echo _('Node')?>"+"</th><td>"+k+"</td></tr>";
						str += "<tr><th> "+"<?php echo _('Location')?>"+"</th><td>"+data.nodes[k].nodeLocation+"</td></tr>";

						var data_mqtt =apeend_echolink(k);

						if(data_mqtt == null)
						{ 


						str += "<tr><th> "+"<?php echo _('QTH')?>"+"</th><td>"+node_location_name_qth+"</td></tr>";	
						str += "<tr><th> "+"<?php echo _('Receiver')?>"+"</th><td>"+node_location_name+"</td></tr>";
						str += "<tr><th> "+"<?php echo _('Frequency')?>"+"</th><td>"+Freqvensy+"</td></tr>";
						str += "<tr><th> "+"<?php echo _('Signal')?>"+"</th><td class='text-nowrap'>"+"<canvas id=\"bar_station\">&nbsp;&nbsp;&nbsp; </canvas> <span id=\"level\"></span>"+"</td></tr>";
						
						}
						else
						{


							console.log(data_mqtt);
							str += "<tr><th> "+"<?php echo _('Mode')?>"+"</th><td>"+data_mqtt['mode']+"</td></tr>";
							str += "<tr><th> "+"<?php echo _('Call')?>"+"</th><td>"+data_mqtt['node']+"</td></tr>";
							str += "<tr><th> "+"<?php echo _('Signal')?>"+"</th><td class='text-nowrap'>"+"<canvas id=\"bar_station\">&nbsp;&nbsp;&nbsp; </canvas> <span id=\"level\"></span>"+"</td></tr>";

						}
						

		
						
						str += "</table></div>";
						
                          	  	
						 $("#station_data").html(str);
							create_bar_rssi('bar_station');



						if(data_mqtt == null)
							update_bar('bar_station',node_location_value,0,node_collors[k]['text']);
						else
							update_bar('bar_station',100,0,node_collors[k]['text']);
						
							$('#level').html(node_location_value+"%");

						 
						 //console.log(data.nodes[k]);
						 $("#station_data").css("background-color",node_collors[k]['color']);
						 $("#station_data").css("color",node_collors[k]['text']);
						 
						 
						 
		
                	  	}
						
            	  	}
        	  	}

        	  		

    	  	  }

    	  	get_tg(current_tg);
    	  	$("#current_tg_page").html(current_tg);

    	  	setTimeout(reflector_handler, 1000); 

    	 }).fail(function() { console.log("Data eror"); interval = setTimeout(reflector_handler, 800);    });


	
	

}







var mqtt;
var reconnectTimeout = 2000;
<?php if($use_mqtt == "true")
{?>
var host="<?php echo $mqtt_host;?>"; //change this
var port=<?php echo $mqtt_port;?>;

<?php }
else
{
?>

	var host=""; //change this
	var port="";
<?php

}

?>
var mqtt_station_array = new Array();

function onConnect() {
// Once a connection has been made, make a subscription and send a message.

    console.log("Connected ");
    mqtt.subscribe("#");
}
	


function MQTTconnect() {
<?php if($use_mqtt == "true")
{?>

	var number = Math.random() // 0.9394456857981651
	number.toString(36); // '0.xtis06h6'
	var id = number.toString(36).substr(2, 9); // 'xtis06h6'
	id.length >= 9; // false

	
	console.log("connecting to "+ host +" "+ port);
	mqtt = new Paho.MQTT.Client(host,port,("portal"+id));
	//document.write("connecting to "+ host);
	var options = {
		useSSL:true,
		timeout: 10,
		onSuccess: onConnect,
		onFailure: onFailure,
		
	  
	 };
	
	mqtt.onMessageArrived = onMessageArrived
	mqtt.onConnectionLost = onConnectionLost;  // Callback when lost connectio
		
	mqtt.connect(options); //connect
<?php } ?>	
	}


function onConnectionLost(resObj) {
    console.log("Lost connection to " + resObj.uri + "\nError code: " + resObj.errorCode + "\nError text: " + resObj.errorMessage);
    if (resObj.reconnect) {
        console.log("Automatic reconnect is currently active.");
    } else {
        console.log("Lost connection to host.");
        setTimeout(MQTTconnect, reconnectTimeout);
    }
}

function onFailure(message) {
	console.log("Connection Attempt to Host "+host+"Failed");
	setTimeout(MQTTconnect, reconnectTimeout);
}

var mqtt_station_array = new Array();

function onMessageArrived(msg){



	var payload_topic =  msg.destinationName.split("/"); 



	if(mqtt_station_array[payload_topic[0]]=== undefined)
	{
		mqtt_station_array[payload_topic[0]] =new Array();
		mqtt_station_array[payload_topic[0]] ["RSSI"] ="-200";
		mqtt_station_array[payload_topic[0]] ["Sval"] ="S0";

	}
	

	mqtt_station_array[payload_topic[0]] [payload_topic[1]] =msg.payloadString;


	
	//mqtt_station_array[payload_topic[0]][payload_topic[1]] = msg.payloadString;
	//console.log(mqtt_station_array);
	
	out_msg="Message received "+msg.payloadString+"<br>";
	out_msg=out_msg+"Message received Topic "+msg.destinationName;




	echolink_msg(msg);
	

}


var echolink_array = new Array();
var echolink_length = new Array();
var echolink_length_old = new Array();
var echolink_talker = new Array();
var echolink_Mode = new Array();


function echolink_msg(msg)
{
	var payload_topic =  msg.destinationName.split("/"); 

	if(payload_topic[1] == "Aux" && payload_topic[3] == "List")
	{
	
		//delete echolink_array[payload_topic[0]][payload_topic[2]];
		var data =msg.payloadString;
// 		console.log(data.split("\n"));
		var array =data.split("\n");
		var last = array.pop();


		//console.log(echolink_array[payload_topic[0]]);

		if (echolink_array[payload_topic[0]] == undefined) {
			
			echolink_array[payload_topic[0]] = new Array();
		}
	
		echolink_array[payload_topic[0]][payload_topic[2]] = array;
		//echolink_Mode[payload_topic[0]] =  payload_topic[2];
		



		
		
//     	echolink_array[payload_topic[0]][payload_topic[3]] =;
//     	console.log(payload_topic);

	}

	if(payload_topic.length >2)
	{

	
    	if(payload_topic[2] == "EchoLink" && payload_topic[3] == "Size")
    	{
    		echolink_length[payload_topic[0]] =msg.payloadString;
    
    	}
    
    	//(payload_topic[2] == "EchoLink" &&  
    	if( payload_topic[3] == "Talker")
    	{
    		//console.log("a");
    		
    		var incoming_message = msg.payloadString;
    		var incoming_message_array = incoming_message.split(' ');
    		//var talker_state =  incoming_message_array[2];
    		//var talker_action = talker_state.replace(/(\r\n|\n|\r)/gm," ");
    
    		//if(talker_action.trim() == 'stop')
    		//	echolink_talker[payload_topic[0]] ="";
    		//if(talker_action.trim() == 'start')
    			echolink_talker[payload_topic[0]] = incoming_message.replace(/(\r\n|\n|\r)/gm,"");
    		
    		//if(msg.payloadString == )
    		//echolink_length[payload_topic[0]] =;

        	//console.log(echolink_talker);
    
    	}
	}

	
	
}

function apeend_echolink(stn)
{


    if(echolink_array[stn] != null)
    {
    	//console.log(obj);
    	
    	$('#group-of-'+stn).html('');

			//echolink_length_old[stn]  = echolink_length[stn]


     	
			//console.log(obj.qth.length);
			
			for(var mode in echolink_array[stn])
			{
			

			    
            	for(var a in echolink_array[stn][mode])
            	{
  
                	var rxobj = new Array();
         
                	var name =echolink_array[stn][mode][a];
    
                	//var mode = echolink_Mode[stn] ;
                	//console.log(echolink_array[stn][a].trim());
                	//console.log(echolink_talker[stn]);
                	mode_id= mode;

    
                	if(echolink_array[stn][mode][a].trim() == echolink_talker[stn])
                	{
                    	var array = new Array();;
                    	array['node'] = echolink_array[stn][mode][a].trim();
                    	array['mode'] =mode ;
                    	return array;
                    	
							
                	}
               
            
          
    
  
        		}

			}

    }


	return null;


}









</script>


<body>


	<div class="container-fluid">





		<div class="card">


				<div class="card-header bg-dark text-white"><?php echo _("Current talker TG:");?> <span id="current_tg_page"></span> </div>
				<div class="card-body" id="station_data"></div>



		
		</div>
		<br />

		<div class="card shadow mb-4">


			<div
				class="card-header py-3 d-flex flex-row align-items-center justify-content-between navbar-dark   text-white bg-dark">
				<h6 class="m-0 font-weight-bold text-white"><?php echo _('Last heard')?></h6>
				<div class="dropdown no-arrow ">
					<a class="dropdown-toggle text-white" href="#" role="button"
						id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
						aria-expanded="false"> <i
						class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400 "></i>
					</a>
					<div
						class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
						aria-labelledby="dropdownMenuLink" style="">
						<div class="dropdown-header">Åtgärder</div>


					</div>
				</div>
			</div>




			<div class="">
				<div class="card-body">











	




						<table id="last_heard_table" class="table table-striped table-sm">
							<thead class="dash_header">
								<tr>
									<th><?php echo _('Node')?></th>
									<th><?php echo _('Location')?></th>
									<th>&nbsp;</th>
									<th><?php echo _('TG')?></th>
									<th><?php echo _('TG Name')?></th>
									<th><?php echo _('Time')?></th>
									<th><?php echo _('Duration')?></th>

								</tr>
							</thead>
							<tbody>

							</tbody>


						</table>
				





				</div>
			</div>


		</div>
	</div>

</body>

</html>


