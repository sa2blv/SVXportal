<?php

include 'config.php';
$link->set_charset("utf8");
include 'function.php';
set_laguage();


?>
<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=Cp1252">
<title><?php echo gettext('Receiver Monitor')?></title>


<link
	href="https://fonts.googleapis.com/css?family=Architects+Daughter&display=swap"
	rel="stylesheet">
<link rel="stylesheet" href="css.css">
<link rel="icon" type="image/png" href="tower.svg">
<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>

<!-- Latest compiled and minified JavaScript -->
<script type="text/javascript" src="lib/jquery.min.js"></script>

<script src="./lib/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="lib/css/bootstrap.min.css">


<script>

function random_css_collor()
{
	  return "hsl(" + 360 * Math.random() + ',' +
      (25 + 70 * Math.random()) + '%,' + 
      (85 + 10 * Math.random()) + '%)'
}


$(document).ready(function(){
	add_header();
	generate_coulor();
	call_svxrefelktor();
	MQTTconnect();

});

var interval;
var totalSeconds =  new Array();
var current_talker= new Array();
var tg_collors = new Array();
function add_header()
{

	$('#Reflektortable').html('<thead class="thead-dark"><tr><th scope="col" class="col-xs-2 text-left" ><?php echo _('Callsign')?> &emsp;&emsp;&emsp;&emsp;</th><th scope="col" class="col-xs-1" ><?php echo _('Location')?></th><th scope="col" class="col-xs-1 text-left"><?php echo _('TG#')?></th><th scope="col" class="col-xs-1"></th><th scope="col" class="col-xs-2"><?php echo _('Receiver')?></th><th scope="col" class="col-xs-1"><?php echo _('Signal')?></th><th scope="col" class="col-xs-2"><?php echo _('Frequency')?></th><th class="col-xs-2"><?php echo _('Talk time')?></th></tr></thead>');
 	
}

var mqtt;
var reconnectTimeout = 2000;
var host="mqttportal.drift.sm2ampr.net"; //change this
var port=10001;

var mqtt_station_array = new Array();

function onConnect() {
// Once a connection has been made, make a subscription and send a message.

    console.log("Connected ");
    mqtt.subscribe("#");
}
	


function MQTTconnect() {
	console.log("connecting to "+ host +" "+ port);
	mqtt = new Paho.MQTT.Client(host,port,"clientjs");
	//document.write("connecting to "+ host);
	var options = {
		useSSL:true,
		timeout: 10,
		onSuccess: onConnect,
		onFailure: onFailure,
		
	  
	 };
	
	mqtt.onMessageArrived = onMessageArrived
		

	 
	mqtt.connect(options); //connect
	
	}

function onFailure(message) {
	console.log("Connection Attempt to Host "+host+"Failed");
	setTimeout(MQTTconnect, reconnectTimeout);
}
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
	//console.log(out_msg);

}











function remove_notgouiltychar(string)
{
	string= string.replace("(", "");
	string= string.replace(")", "");
	string= string.replace(".", "");
	string= string.replace(",", "");
	string= string.replace("'", "");
	return string;
}

function generate_coulor()
{
    $.getJSON( "<?php echo $serveradress ?>", function( data ) {
    
    
    	for(var k in data.nodes){
    		
    		for(var nodes in data.nodes[k].monitoredTGs){
    			//tg_collors[data.nodes[k].monitoredTGs[nodes]]['color']="";	
    			tg_collors[data.nodes[k].monitoredTGs[nodes]]= new Array();
    			tg_collors[data.nodes[k].monitoredTGs[nodes]]["id"] =data.nodes[k].monitoredTGs[nodes];
    			tg_collors[data.nodes[k].monitoredTGs[nodes]]["color"] =random_css_collor();   
    			tg_collors[data.nodes[k].monitoredTGs[nodes]]["TXT"] ="";
    			totalSeconds[data.nodes[k].tg]=0;
    		}
    
    		tg_collors[data.nodes[k].tg]= new Array();
    		tg_collors[data.nodes[k].tg]["id"] =data.nodes[k].tg;
    		tg_collors[data.nodes[k].tg]["color"] =random_css_collor();
    		tg_collors[data.nodes[k].tg]["TXT"] ="";
    		totalSeconds[data.nodes[k].tg]=0;
    
    	}
    	
    	<?php

    			$result = mysqli_query($link, "SELECT * FROM `Talkgroup` ORDER BY `Talkgroup`.`TG` ASC");

    			// Numeric array

    			// Associative array
    			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    ?>
    				tg_collors[<?php echo $row["TG"]?>]= new Array();
            		tg_collors[<?php echo $row["TG"]?>]["id"] =<?php echo $row["TG"]?>;
            		tg_collors[<?php echo $row["TG"]?>]["color"] = "<?php echo $row["Collor"]?>";      
            		tg_collors[<?php echo $row["TG"]?>]["TXT"] = "<?php echo $row["TXT"]?>";             
    <?php }?>

    for(var k in tg_collors){
        
      	$('#selects').append($('<option>', {
      	    value: k,
      	    text: k+"		"+tg_collors[k]["TXT"],
      	  style:"background-color: "+tg_collors[k]["color"] 
      	}));
      }
	
    });



    
}
var filter_station = "";
function update_filter(value) {
	filter_station = value;
//	$('#Reflektortable').html("<th>Callsign</th><th>TG</th><th>Ver</th><th>Monitored TGs</th>");
	$('#Reflektortable').find('tbody').detach();
	add_header();
	call_svxrefelktor();

}




function call_svxrefelktor() {
	
$.getJSON( "<?php echo $serveradress ?>", function( data ) {
	if(filter_station != "")
	{
    	for(var k in data.nodes){
    		if(data.nodes[k].tg != filter_station)
    		{
    			delete data.nodes[k];
    		}
    		
    	}
	}
	//console.log(data);
	

for(var k in data.nodes){
	
	for(var nodes in data.nodes[k].monitoredTGs){
		//tg_collors[data.nodes[k].monitoredTGs[nodes]]['color']="";	   
	}

	tg_collors[data.nodes[k].tg.to]= new Array();
	tg_collors[data.nodes[k].tg.to]["id"] =0;


}

for(var k in data.nodes){
		
    if(data.nodes[k].hidden == true)
    {
    	delete data.nodes[k];
    	
    }
}

for(var k in data.nodes){
	var text =" ";
	if(k == null)
	{
		conole.log("empty data");
		break;
	}
	var printk =remove_notgouiltychar(k)
	
	//k=remove_notgouiltychar(k);

	
	


		var image= '<img src="images/talking.gif" alt="talk" id="talking" width="25px">';	

		if(data.nodes[k].NodeLocation == null)
		{
			
			data.nodes[k].NodeLocation=data.nodes[k].nodeLocation; 
			if(data.nodes[k].NodeLocation == null)
			{
				data.nodes[k].NodeLocation=" ";
			}
		}
		


 		
    	if(document.getElementById('row'+printk))
       	{

    		var rssi_str ="";
    		if(mqtt_station_array[k])
    		{
    			rssi_str ='R <canvas id="bar_RSSI_'+printk+'"></canvas><br />S ';

    		}
    		else
    		{
    			rssi_str ='&nbsp;';
    		}

    	
        		
			

           	
    		if(data.nodes[k].isTalker == false)
    		{
	 	  		$('#row'+printk+'').html('<td>'+k+'</td>'+'<td>'+data.nodes[k].NodeLocation+'</td>'+'<td>'+data.nodes[k].tg+'</td>'+'<td class="">'+rssi_str+'<canvas id="bar_'+printk+'"></canvas></td><td id="reciver_'+printk+'">  </td></td><td id="value_k'+printk+'">0%</td><td id="freq_row'+printk+'"></td><td class="flex-nowrap"> - </td>');
	 	  		$('#row'+printk+'').removeClass("font-weight-bold");
	 	  		
    			
    		}
    		else
    		{
	 	  		$('#row'+printk+'').html('<td>'+k+'</td>'+'<td>'+data.nodes[k].NodeLocation+'</td>'+'<td>'+data.nodes[k].tg+'</td>'+'<td class="" >'+rssi_str+'<canvas id="bar_'+printk+'"></canvas></td><td id="reciver_'+printk+'">  </td><td id="value_k'+printk+'">0%</td><td id="freq_row'+printk+'"></td><td class="flex-nowrap" ><label id="minutes_'+data.nodes[k].tg+'">00</label>:<label id="seconds_'+data.nodes[k].tg+'">00</label></td>');
	 	  		$('#row'+printk+'').addClass("font-weight-bold");	

	 	  		
	    	}
	    	if(data.nodes[k].tg != 0)
	    	{
		    	if(tg_collors[data.nodes[k].tg]== null)
		    	{
		    		tg_collors[data.nodes[k].tg]= new Array();
		    		tg_collors[data.nodes[k].tg]["id"] =data.nodes[k].tg;
		    		tg_collors[data.nodes[k].tg]["color"] =random_css_collor();
		    		totalSeconds[data.nodes[k].tg]=0;
		    		tg_collors[data.nodes[k].tg]["TXT"] ="";

		          	$('#selects').append($('<option>', {
		          	    value: data.nodes[k].tg,
		          	    text: data.nodes[k].tg+"		"+tg_collors[data.nodes[k].tg]["TXT"],
		          	  style:"background-color: "+tg_collors[data.nodes[k].tg]["color"] 
		          	}));
		          }
		    		
		    	
	    		$('#row'+remove_notgouiltychar(k)+'').addClass("table-secondary");
    			$('#row'+remove_notgouiltychar(k)+'').css('background-color', tg_collors[data.nodes[k].tg]["color"]);
    			$('#row'+remove_notgouiltychar(k)+'').removeClass("table-secondary");
	    	}
	    	else
	    	{
	    		$('#row'+remove_notgouiltychar(k)+'').css('background-color', "");
	    		$('#row'+remove_notgouiltychar(k)+'').addClass("table-secondary");
	    	}
	    	
	    	create_bar('bar_'+remove_notgouiltychar(k));
	    	
    		if(mqtt_station_array[k])
    		{
    			create_bar_rssi('bar_RSSI_'+remove_notgouiltychar(k));
    			
    		}
    		//create_bar_rssi('bar_RSSI_'+remove_notgouiltychar(k));
	    	
    	 }
    	 else
    	 {
	  		$('#Reflektortable').append('<tbody class="table-striped"><tr data-toggle="collapse" data-target="#group-of-'+printk+'" aria-expanded="false" aria-controls="group-of-'+printk+'" class="" id="row'+printk+'"><td>'+k+'</td>'+'<td>'+data.nodes[k].NodeLocation+'</td>'+'<td>'+data.nodes[k].tg+'</td>'+'<td ><canvas id="bar_RSSI_'+printk+'"></canvas><canvas id="bar_'+printk+'"></canvas></td><td id="reciver_'+printk+'">  </td><td id="value_k'+printk+'">0%</td></td><td id="freq_row'+printk+'"></td><td class="flex-nowrap"><label id="minutes_'+data.nodes[k].tg+'"></label><label id="seconds_'+data.nodes[k].tg+'"></label></td> </tr> </tbody>');
	  		create_bar('bar_'+printk);
	  		//create_bar_rssi('bar_RSSI_'+printk)
	    }
		  if(current_talker[data.nodes[k].tg] == k)
	  {
		//totalSeconds=0;
	  }	

	
    if(data.nodes[k].isTalker == true)
	{

        ++totalSeconds[data.nodes[k].tg];
		var minutesLabel = document.getElementById("minutes_"+data.nodes[k].tg);
       	var secondsLabel = document.getElementById("seconds_"+data.nodes[k].tg);
		current_talker[data.nodes[k].tg] = k;
            secondsLabel.innerHTML = pad(((totalSeconds[data.nodes[k].tg]/2)%60).toFixed(0));
            minutesLabel.innerHTML = pad(parseInt(totalSeconds[data.nodes[k].tg]/300));
 
	}
    else if(current_talker[data.nodes[k].tg] == k)
    {
    	totalSeconds[data.nodes[k].tg]=0;
    	current_talker[data.nodes[k].tg]=0;
    	
		

    }
// if qth is defined

    for(var qth in data.nodes[k].qth)
    {
    	for(var qth1 in data.nodes[k].qth[qth].rx)
        	{
    
  
           	var qth_name =data.nodes[k].qth[qth].rx[qth1].name;
           	var rx_active =data.nodes[k].qth[qth].rx[qth1].active;
           	var rx_sql =data.nodes[k].qth[qth].rx[qth1].sql_open;
           	var sql = data.nodes[k].qth[qth].name;
           	var value =data.nodes[k].qth[qth].rx[qth1].siglev;
           	var Freqvensy =String(data.nodes[k].qth[qth].rx[qth1].freq);

           	Freqvensy = parseFloat(Freqvensy);
           	Freqvensy = Freqvensy.toFixed(4); 
           	Freqvensy = String(Freqvensy);
           
      
           	var name_id =data.nodes[k].qth[qth].name+qth1;
           	name_id = name_id.trim();
           	if(value == undefined)
               	value =0;
        	
           var qth_html_add;
     	   var class_row=""
           if(rx_active == true)
           {
        	   class_row = ""
           }


     	  if(document.getElementById('row'+remove_notgouiltychar(name_id)))
     	  {

              qth_html_add ='<td> * '+qth_name+'</td><td></td><td></td><td colspan="1" id="td'+k+'_'+qth_name+'"><canvas id="bar_'+printk+'_'+qth_name+'"></canvas></p> </td><td></td><td>'+parseInt(value)+'%</td><td>'+Freqvensy+'</td><td></td>';
              $('#row'+remove_notgouiltychar(name_id)).html(qth_html_add);
              $('#row'+remove_notgouiltychar(name_id)).addClass("class_row");
              
              
              //create_bar('bar_'+k);
     	  }
     	  else
     	  {
              qth_html_add ='<tbody id="group-of-'+printk+'" class="collapse"><tr class="table-striped  '+class_row+' table-borderless" id="row'+name_id+'"  ><td> * '+qth_name+'</td><td></td><td></td><td colspan="" id="td'+printk+'_'+qth_name+'"><canvas id="bar_'+printk+'_'+qth_name+'"></canvas> </td><td></td><td>'+parseInt(value)+'%</td><td>'+Freqvensy+'</td><td></td></tr></tbody>';
              $('#Reflektortable').append(qth_html_add);
              $('#row'+remove_notgouiltychar(name_id)+'').removeClass("class_row");
              
     	  }
          
           var canvas = document.getElementById('bar_'+printk+'_'+qth_name);
           var context = canvas.getContext('2d');
           width= 0.2 *window.innerWidth
           var value_scale =width/100;
           canvas.setAttribute('width', width);
     
           canvas.setAttribute('height', 10);
           canvas.setAttribute('style', 'border:1px solid #000000;');
    
           
           context.stroke(); 
           context.fillRect(1, 1 , -0,1); 

       	if(rx_active == true && rx_sql == true)
    	{
    		 
    		context.fillStyle ="#1932F7";
    		update_bar('bar_'+k,value,k);
    		$("#freq_row"+k).html(Freqvensy);

    		if(mqtt_station_array[k])
    		{
    		
    			$("#reciver_"+k).html(Math.round(mqtt_station_array[k]["RSSI"])+" DBm<br/>"+qth_name);
    		}
    		else
    		{
    			$("#reciver_"+k).html(qth_name);
    		}
    		
 		
    	}
    	else if(rx_sql == true)
    	{
    		context.fillStyle ="#E31013";
    
    	}
    	else
    	{
    		context.fillStyle ='black';
    	}
           
    	
       	if(value>=0 && value <100)
       		context.fillRect(1, 1 , (value_scale*value)-3,8); 
    	else if (value >=100)
    		context.fillRect(1, 1 , width-3,8);

    	}
    }

    }
});
    
       



    interval = setTimeout(call_svxrefelktor, 500);   
}
function create_bar(id)
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


function update_bar(id,value,k)
{

	if(mqtt_station_array[k])
	{
		update_bar_rssi('bar_RSSI_'+k,mqtt_station_array[k]["RSSI"],k)
	}
	else
	{
		$("#value_k"+k).html(parseInt(value)+" %")
	}

		
    var canvas = document.getElementById(id);
    var context = canvas.getContext('2d');
    width= 0.2 *window.innerWidth
    var value_scale =width/100;
    canvas.setAttribute('width', width);

    canvas.setAttribute('height', 10);
    canvas.setAttribute('style', 'border:1px solid #000000;');

    
    context.stroke(); 
    context.fillRect(1, 1 , -0,1); 
    context.fillStyle ="#1932F7";
   	if(value>=0 && value <100)
   		context.fillRect(1, 1 , (value_scale*value)-3,8); 
	else if (value >=100)
		context.fillRect(1, 1 , (value_scale*100)-3,8);

    context.rect(10, 10, 150, 100);


}

const scale = (num, in_min, in_max, out_min, out_max) => {
	  return (num - in_min) * (out_max - out_min) / (in_max - in_min) + out_min;
	}

function scale_rssi(val)
{

	console.log(val);
	return (scale(val, -160, -10, 0, 100)); 

	
}



function update_bar_rssi(id,value,k)
{
    var canvas = document.getElementById(id);
    var context = canvas.getContext('2d');
    width= 0.2 *window.innerWidth
    var value_scale =width/100;
    canvas.setAttribute('width', width);

    value=scale_rssi(value)

    canvas.setAttribute('height', 10);
    canvas.setAttribute('style', 'border:1px solid #000000;');

    
    context.stroke(); 
    context.fillRect(1, 1 , -0,1); 
    context.fillStyle ="#1932F7";
   	if(value>=0 && value <100)
   	{
   	   	console.log(value);
   	   	if(value >=0)
   	   	{
       		context.fillStyle ="#FFFF00";
       		context.fillRect(1, 1 , (value_scale*value)-3,8); 
   	   	}

   	   	if(value >= 20)
   	   	{
       		context.fillStyle ="#9ACD32";
       		context.fillRect((value_scale*20), 1 , (value_scale*(value-20))-3,8); 

   	   	}
   	   	if(value >= 45)
   	   	{
       		context.fillStyle ="#FF4500";
       		context.fillRect((value_scale*45), 1 , (value_scale*(value-45))-3,8); 

   	   	}
   	}
	else if (value >=100)
		context.fillRect(1, 1 , (value_scale*100)-3,8);

    context.rect(10, 10, 150, 100);

	$("#value_k"+k).html(mqtt_station_array[k]["Sval"]+"<br />"+parseInt(value)+" %")
	
}






        function setTime()
        {
    
        }

        function pad(val)
        {
            var valString = val + "";
            if(valString.length < 2)
            {
                return "0" + valString;
            }
            else
            {
                return valString;
            }
        }

</script>



</head>

<body>

<?php
?>
<div class="w-screen ">
    <nav class="navbar navbar-dark bg-dark text-light">
    			<label for="selects"><?php echo _('Talkgroup filter')?>:</label><select id="selects"  class="w-25" onchange="update_filter(this.value)">
    				<option value="">-- <?php echo _('All')?> --</option>
    			</select>
    <a href="index.php" onclick="" class="btn btn-outline-success my-2 my-sm-0"
    						id="menu-toggle"><?php echo _('Back')?></a>
    		</nav>
    		
    <table id="Reflektortable" class="table  table-hover">
    
    
	</table>
</div>

</body>


</html>
