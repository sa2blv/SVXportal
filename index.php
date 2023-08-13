<?php
header('Access-Control-Allow-Origin: <?php echo $serveradress ?>');
include "config.php";
include 'function.php';
include "Mqtt_driver.php";
define_settings();
set_laguage();

?>
<?php $start_time = microtime(true); ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />

<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title> SVX Portal <?php echo PORTAL_VERSION ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<meta charset="UTF-8">
<meta name="description" content="Svxportal For SvxReflektor">
<meta name="keywords" content="svxlink,svxreflektor,sa2blv">
<meta name="author" content="Peter SA2BLV">

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
<script src="./js/div_recivers.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>



<?php include "div_reciver.php";?>


<script type="text/javascript">

var get_push ="0";
var kill_loop =0;
var secret_santa =0;



function show_notifications_nodes()
{
	alert("Notifcation beta");
		get_push = "1";
	
}

function go_to_station(station)
{
  $('#Reflector').removeClass("active");
  $('#stationinfor').tab("show");
  Load_station_intofmation(station);
  fix_sidemeny_active('stationinfor');
}

function fix_sidemeny_active(current)
{
	$('#sidebar-wrapper a').removeClass('active');


	$('#sidebar-wrapper a').each(function() {

		var href = $( this ).attr('href');
		if(href == ("#"+current))
		{
			 $( this ).addClass('active');
		}

		
	});

	
}



function toogle_menu()
{
        $("#wrapper").toggleClass("toggled");	
  
        
   
        
        setTimeout(function(){
        	   map.updateSize();
        	   player_move();
        	   
        
        }, 500);
}



function Reset_password() {
	  var Reset_password = prompt("<?php echo addslashes (_("Please enter your username"));?>", "");
	  if (Reset_password != null) 
      {


		  $.post( "admin/reset_user_password.php", { Reset_password: Reset_password})
		  .done(function( data ) 
		   {
			   if(data == 2)
			   {
				   alert("<?php echo addslashes(_("User doesn't exist!"))?>");
			   }

			   if(data == 1)
			   {
				   alert("<?php echo addslashes (_("An E-mail has been sent to user mail"))?>");
			   }
    
		    
		  });

		  

		  

	  }
	}




/*
 * 
Load the page form ctcss map table.
mak it in javascipt for speed
 
 */


function Load_ctcss_mapping()
{

	$.get( "ctcss_map_table.php", { nohad: "1"} )
	  .done(function( data ) {
	   $("#Table_ctcss").html(data);
	  });

}






var refelktor_address="<?php echo $serveradress ?>";
//<![CDATA[
	
	$.datepicker.regional['phplang'] = {
    closeText: '<?php echo _('Close') ?>', // set a close button text
    currentText: '<?php echo addslashes(_('Today')) ?>', // set today text
    monthNames: ['<?php echo _('January') ?>','<?php echo _('February') ?>','<?php echo _('March') ?>','<?php echo _('April') ?>','<?php echo _('May') ?>','<?php echo _('June') ?>','<?php echo _('July') ?>','<?php echo _('August') ?>','<?php echo _('September') ?>','<?php echo _('October') ?>','<?php echo _('November') ?>','<?php echo _('December') ?>'], // set month names
    dayNamesMin: ["<?php echo _('Su') ?>", "<?php echo _('Mo') ?>", "<?php echo _('Tu') ?>", "<?php echo _('We') ?>", "<?php echo _('Th') ?>", "<?php echo _('Fr') ?>", "<?php echo _('Sa') ?>"],
    dayNames: ['<?php echo _('Monday') ?>','<?php echo _('Tuesday') ?>','<?php echo _('Wednesday') ?>','<?php echo _('Thursday') ?>','<?php echo addslashes (_('Friday')) ?>','<?php echo _('Saturday') ?>','<?php echo _('Sunday') ?>'], // set days names
    dateFormat: 'dd/mm/yy' // set format date
};
	
	var station_talkgroup= new Array();

  // declare player object once
  
var myPlaylist ;

  var reflector_init_data;

$(document).ready(function(){

  if ($(window).width() < 922) {
	   toogle_menu();
	  } 
  

  
  	
    //call_svxrefelktor();
    reflector_handler();
    add_node_collors();
    load_reflector();

    // data the is bein loaded once 
    $.getJSON( "<?php echo $serveradress ?>", function( data ) 
    {
    	reflector_init_data=data;
    	generate_coulor(reflector_init_data);
    	
    });
    
	MQTTconnect();
	request_notification();
    var x = document.getElementById("beep_message"); 
    x.load()
    
    
     setTimeout(
    	     function(){

        if($("#menuNodeCount").html() == "")
        {
        	create_message_toast("<?php echo _('No Resonse from reflektor server') ?>","<?php echo _("System failure")?>","red",true);
        
        }

	}, 6000);
    




	$.datepicker.setDefaults($.datepicker.regional['phplang']);

	$( "#datepicker" ).datepicker({<?php echo get_oldest_file();?>maxDate:0,firstDay: 1, dateFormat: 'yy-mm-dd' });


		 myPlaylist = new jPlayerPlaylist({
			jPlayer: "#jquery_jplayer_N",
			cssSelectorAncestor: "#jp_container_N"
		}, [
		], {
			playlistOptions: {
				enableRemoveControls: true
			},
			swfPath: "./dist/jplayer",
			supplied: "webmv, ogv, m4v, oga, mp3",
			useStateClassSkin: true,
			autoBlur: false,
			smoothPlayBar: true,
			keyEnabled: true,
			size: {width: "100%", height: "0px"}
		});

		$( "#Datepicker_graph" ).datepicker({<?php echo startdate($start_date_defined)?>changeYear:true,changeMonth: true,maxDate:0,firstDay: 1, dateFormat: 'yy-mm-dd' });


	      // javascipt links to page tabs
		  let url = location.href.replace(/\/$/, "");
		  
		  if (location.hash) {
		    const hash = url.split("#");

		    $('#Reflector').removeClass("active");
		    $('#'+hash[1]+'').tab("show");
		    url = location.href.replace(/\/#/, "#");
		    history.replaceState(null, null, url);
		    setTimeout(() => {
		      $(window).scrollTop(0);

				console.log(hash[1]);
				if(hash[1] == "map_repeater")
				{
					hide_menu_click();
					setTimeout(function(){
							map.updateSize();
							connect_reflector();
					   },300); 

				}
				if(hash[1] == "Statistics")
				{
					get_statistics();

				}
				if(hash[1] == "listen")
				{
					hide_menu_click();
					player_move();
					get_audio_date();

					
					

				}
				if(hash[1] == "Table_ctcss")
				{
					Load_ctcss_mapping();

					
					

				}
				if(hash[1] == "Last_heard_page")
				{
					activate_last_heard();

					
					

				}
								


				
				
				if(hash[2] == "menu_hide")
				{
				      $( "#wrapper" ).removeClass( "toggled" );
				}

				fix_sidemeny_active(hash[1]);
				



				


				
					
		     
		      
		    }, 400);
		  } 



		  
		

var interval;
var totalSeconds = 0;
var current_talker ="";

$("#jquery_jplayer_N").bind($.jPlayer.event.timeupdate, function(event) { 
    var currentTime = Math.floor(event.jPlayer.status.currentTime)
    var TotaltimeTime = Math.floor(event.jPlayer.status.duration)

$.post( "signal.php", { time: (TotaltimeTime-currentTime), file: event.jPlayer.status.src })
.done(function( data ) {

	//console.log(data);
	if(data != "")
	{
    var Json_data = JSON.parse(data);
    




    $('#Reciverbars_player').html("");
	if(Json_data.Nodename == undefined)
	{
		Json_data.Nodename ="<?php echo _('No data');?>";
	}

	

    
	$('#Reciverbars_player').append('<p>'+Json_data.Nodename+'</p><canvas id="canvpr"></canvas><br/>');
	create_bar_rx(Json_data.Siglev,'canvpr',true);
    
    $('#signalpressent').html(Json_data.Siglev);

		for(var k in Json_data.Subreciver){

			$('#Reciverbars_player').append('<p>'+Json_data.Subreciver[k]['Nodename']+'</p><canvas id="canvp'+k+'"></canvas><br/>');
			create_bar_rx(Json_data.Subreciver[k]['Siglev'],'canvp'+k,false);
			
		}

	}


    

});

    

 // Get the input field
    var input = document.getElementById("password2");

 if(input != undefined)
 {
    // Execute a function when the user releases a key on the keyboard
    input.addEventListener("keyup", function(event) {
      // Number 13 is the "Enter" key on the keyboard
      if (event.keyCode === 13) {
        // Cancel the default action, if needed
        event.preventDefault();
        // Trigger the button element with a click
        login_form();
      }
    }); 
 }

    


});
var start_talk_var;



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


      
    	interval = setTimeout(call_svxrefelktor(data), 50);  
    	if(kill_loop == 0 )
//    	{
//    	  kill_loop = 0;
    	  interval = setTimeout(update_tx_station_loop(data), 100);  

//    	  console.log("map");
    
    
  //     }
  
    	interval = setTimeout(div_call_svxrefelktor(data), 50);  

    
    		
    	interval = setTimeout(reflector_handler, 800);  
    	var date = new Date().toLocaleTimeString();
    	
    	console.log("Demon running "+date);


    	get_active_last();
  	  }
    }).fail(function() { console.log("Data eror"); interval = setTimeout(reflector_handler, 800);    });




}
function create_bar_rx(value,element,rx_sql)
{
    var canvas = document.getElementById(element);
    var context = canvas.getContext('2d');
    width= 0.24 *window.innerWidth
    var value_scale =width/100;
    canvas.setAttribute('width', width);

    canvas.setAttribute('height', 10);
    canvas.setAttribute('style', 'border:1px solid #000000;');

    
    context.stroke(); 
    context.fillRect(1, 1 , -0,1); 
    var rx_active=true;

    

	if(rx_active == true && rx_sql == true)
	{
		 
		context.fillStyle ="#1932F7";
	
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
var old_json_pass = "";
var totalSeconds =  new Array();
var current_talker= new Array();



function call_svxrefelktor(data) {
var node_count =0;
var talkgroups_active = new Array();




	

for(var k in data.nodes)
{
		
	    if(data.nodes[k].hidden == true && secret_santa == 0) 
	    {
	    	delete data.nodes[k];
	    }

	    if ( k.includes("/")) {
			var new_call = k.replace("/",'-');
	    	data.nodes[new_call] = data.nodes[k]; 
	        delete data.nodes[k];
	    }
	    
	    
}

	
	

//$('#Reflektortable').html('
// create first dryrun
var dryrun =0;
if(old_json_pass =="")
{
	old_json_pass = data;
	dryrun=1;
	
}
var clear =0;
// Reinit_table_sort
if(Object.keys( data.nodes).length > Object.keys( old_json_pass.nodes).length)
{
	$("#Reflektortable > tbody").empty("");
	dryrun=1;
	
}




		
for(var k in data.nodes)
{

	if($('#Reflektortable_row_'+k).length == 0)
	{
	
		$('#Reflektortable > tbody ').append('<tr id ="Reflektortable_row_'+k+'"></tr>');

	}

	

}
$('tr[id^="Reflektortable_row"]').each(function( index ) {

  var elenent_id=$( this ).attr('id');
  res = elenent_id.split("_");

  var nodes = data.nodes;
  if(!nodes.hasOwnProperty(res[2]))
  {
	  $( this ).addClass('table-warning text-muted');
  }
  else
  {
	  $( this ).removeClass('table-warning text-muted');
  }

  

  
});




for(var k in data.nodes){
	node_count++;
	var text =" ";



	if(talkgroups_active[data.nodes[k].tg] == undefined)
		talkgroups_active[data.nodes[k].tg]  =1;
	else
		talkgroups_active[data.nodes[k].tg] =talkgroups_active[data.nodes[k].tg] +1;

	

	if((JSON.stringify(data.nodes[k]) != JSON.stringify(old_json_pass.nodes[k]) || dryrun == 1 || data.nodes[k].isTalker == true))
    	{
    	for(var nodes in data.nodes[k].monitoredTGs){
    	
    	   text = text  +data.nodes[k].monitoredTGs[nodes].toString() +" "
    	  
    	}
    
    		var image= '<img src="images/talking.gif" alt="talk" id="talking" width="25px">';	

    
    		
         if(get_push == "1")
         {
             
    
                if(station_talkgroup[k] == undefined )
                {
                	station_talkgroup[k] = data.nodes[k].tg;
                }
    
        		if(station_talkgroup[k] != data.nodes[k].tg)
        		{
        			if(data.nodes[k].isTalker == true)
        			{
            			create_message_toast("Started to talk on TG# "+data.nodes[k].tg,"Station "+k+"",0,"green","true");
            			station_talkgroup[k] = data.nodes[k].tg;
        			}
        
        		}
        		else
        		{
        			if(data.nodes[k].isTalker == true)
        			{
        				station_talkgroup[k] = data.nodes[k].tg;
        			}
        
        		}
         }
		 var tg_open_str ="";
         if(data.nodes[k].tg > 0 )
         {
        	 tg_open_str ="onclick=\"open_tg_window('"+data.nodes[k].tg+"')\"";
        	 
         }
         var is_resstricted_collor =" style=\"cursor: pointer;\"  ";

        if(data.nodes[k].restrictedTG == true)
     	{
        	is_resstricted_collor ="style=\"cursor: pointer; color: #FF00FF;font-weight: bold;\"";
     	}
         
       
    		
        if(data.nodes[k].isTalker == false)
    	{
        	$('#Reflektortable_row_'+k).removeClass( "table-info" );
        	$('#Reflektortable_row_'+k).html('<td class="text-nowrap" onclick="go_to_station(\''+k+'\')" style="cursor: pointer;">'+k+'</td>'+'<td '+tg_open_str+' '+is_resstricted_collor+'>'+data.nodes[k].tg+'</td>'+'<td class="red_collor"><?php echo _("NO")?></td><td class="text-primary">'+text+'</td><td></td><td></td>');

    	 	 totalSeconds[k]=0;
    
    	}
    	else
    	{
        	if(totalSeconds[k] == undefined )
        		totalSeconds[k]=0;
            	
    		//tr class="table-info">
    		var idns = k;
    		$('#Reflektortable_row_'+k).html('<td class="text-nowrap" onclick="go_to_station(\''+k+'\')" style="cursor: pointer;">'+k+'</td>'+'<td '+tg_open_str+' '+is_resstricted_collor+'>'+data.nodes[k].tg+'</td>'+'<td class="green_collor" ><?php echo _("YES")?></td><td class="text-primary">'+text+'</td><td><label id="Start_talk_'+k+'"></label></td><td  class="d-none d-md-table-cell" ><label id="minutes_'+idns+'">00</label>:<label id="seconds_'+idns+'">00</label></td>');
    		$('#Reflektortable_row_'+k).addClass( "table-info" );
    
             totalSeconds[k]++;
    		var minutesLabel = document.getElementById("minutes_"+idns);
           	var secondsLabel = document.getElementById("seconds_"+idns);
           	var Start_talk_element = document.getElementById("Start_talk_"+k);
           	
    		current_talker = k;
                secondsLabel.innerHTML = pad(totalSeconds[k]%60);
                minutesLabel.innerHTML = pad(parseInt(totalSeconds[k]/60));
    
    
                
    
                var d = new Date();
                d.setSeconds(d.getSeconds() - totalSeconds[k]);
                var h = addZero(d.getHours());
                var m = addZero(d.getMinutes());
                var s = addZero(d.getSeconds());
                Start_talk_element.innerHTML = h + ":" + m + ":" + s;
                
          
     
    	}
    }
}


$('#active_talgroup_table > tbody').html('');

for(var k in talkgroups_active)
{

    $('#active_talgroup_table > tbody').append('<tr><td>'+k+'</td><td>'+talkgroups_active[k]+'</td></tr>');
}


$("#menuNodeCount").html(node_count);

old_json_pass = data;
 




}
function clear_k(k)
{
	console.log(k);
	
	return k;	
}

function addZero(i) {
	  if (i < 10) {
	    i = "0" + i;
	  }
	  return i;
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




$("#wrapper").toggleClass("toggled");
var d = new Date();

var month = d.getMonth()+1;
var day = d.getDate();

var output = d.getFullYear() + '-' +
    (month<10 ? '0' : '') + month + '-' +
    (day<10 ? '0' : '') + day;    

load_today_audio(output);




	// Click handlers for jPlayerPlaylist method demo


	
	

});

//]]>
function listen_live()
{
var station_url = $('#Live_station').val();
var aditional_text = $( "#Live_station option:selected" ).text();


myPlaylist = new jPlayerPlaylist({
	jPlayer: "#jquery_jplayer_N",
	cssSelectorAncestor: "#jp_container_N"
}, [
], {
	playlistOptions: {
		enableRemoveControls: true
	},
	swfPath: "./dist/jplayer",
	supplied: "webmv, ogv, m4v, oga, mp3",
	useStateClassSkin: true,
	autoBlur: false,
	smoothPlayBar: true,
	keyEnabled: true,
	size: {width: "100%", height: "0px"}
});

  myPlaylist.add({
    title:"<?php echo _('Live')?>: " +aditional_text,
    artist:"Svx stream",
    mp3:station_url  
  });
  myPlaylist.play();
}

function listen_live_external()
{
	var station_url = $('#Live_station').val();
	var aditional_text = $( "#Live_station option:selected" ).text();

	window.open(station_url+".m3u", "_blank", ""); 
}
function open_tg_window(tg)
{
	 window.open("last_heard_page.php?TG="+tg, "_blank", "toolbar=no,scrollbars=yes,resizable=yes,width=800,height=800"); 
	
}







function Load_station_intofmation(value)
{
	console.log(value);



	$.get( "station_info.php", { callsign: value, no_header: "true" } )
	  .done(function( data ) {
		  if(value != "")
	    	$('#station_info_html_data').html(data);
		  else
			  $('#station_info_html_data').html("<h3><?php echo _('Pleace select station') ?></h3>");  
	  });
	
}




function Load_log()
{



	$.get( "log.php", {  } )
	  .done(function( data ) {

		  $('#logdiv1').html(data);
			
	  });
	
}



<?php

if($use_mqtt == true){
    
    $mytt_man = new MQtt_Driver;
    $mytt_man-> Set_broker($mqtt_host,$mqtt_port,$mqtt_TLS);
    
    $mytt_man->javascipt();
    
    $mytt_man->Print_hock_on_message();
    
//    $mytt_man->

    
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


function get_audio_from_date(date)
{


	date_loaded = date;


$.getJSON('recording.php?date='+date, function (data) {

	  myPlaylist.remove()
	  var nr =0;
      for (i = 0; i < data.length; i++) {
    	var title ="";
    	  if(data[i].text != null)
    	  {
    		  title =  data[i].text;
    	  }
    
      myPlaylist.add({
        title:title,
        oga:data[i].file
       // poster: "http://www.rfwireless-world.com/images/VHF-UHF-repeater.jpg"
      });
      nr=i;
        
      
   }

      $("#menuaudioCount").html((nr))
  
  
  
   });
myPlaylist.play();

}


function load_today_audio(date)
{

	$.getJSON('recording.php?date='+date, function (data) {


	      $("#menuaudioCount").html((data.length))
	  
	  
	  
	   });
	
}





function Show_all_Recivers()
{
	$( "#Recivers" ).html( "" );


    //do what you need here

<?php
$result = mysqli_query($link, "SELECT * FROM `repeater`");

// Numeric array
$i = 0;
echo " 	multi_stat_change('repeater-info-.json','Menu');";
// Associative array
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

    echo 'setTimeout(function(){';
    if ($i == 0) {

        echo " 	multi_stat_change('repeater-info-" . $row["Name"] . ".json','0');";
    } else {
        echo " 	multi_stat_change('repeater-info-" . $row["Name"] . ".json',".$i.");";
    }

    echo "}, " . (200 + $i * 20) . ");";
    $i++;
}
?>	




}

var tg_collors = new Array();
function generate_coulor(data)
{

	//$.getJSON( "<?php echo $serveradress ?>", function( data ) {
    
    
    	for(var k in data.nodes){
    		
    		for(var nodes in data.nodes[k].monitoredTGs)
        	{
    			//tg_collors[data.nodes[k].monitoredTGs[nodes]]['color']="";	
    			tg_collors[data.nodes[k].monitoredTGs[nodes]]= new Array();
    			tg_collors[data.nodes[k].monitoredTGs[nodes]]["id"] =data.nodes[k].monitoredTGs[nodes];
    			tg_collors[data.nodes[k].monitoredTGs[nodes]]["color"] =random_css_collor();   
    			tg_collors[data.nodes[k].monitoredTGs[nodes]]["TXT"] ="";
    		}
    
    		tg_collors[data.nodes[k].tg]= new Array();
    		tg_collors[data.nodes[k].tg]["id"] =data.nodes[k].tg;
    		tg_collors[data.nodes[k].tg]["color"] =random_css_collor();
    		tg_collors[data.nodes[k].tg]["TXT"] ="";
    		

    
    	}
    	tg_collors[0]= new Array();
    	tg_collors[0]["id"] =0;
    	tg_collors[0]["color"] ="#6c757d";
    	tg_collors[0]["TXT"] ="";
    	
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
	
 //   });



  


    
}
function load_languge(lang)
{
	$.post( "change_languge.php", { locate_lang: lang })
	  .done(function( data ) {
		  location.reload(); 
	  });
	
}
function hide_menu_click()
{

	  if ($(window).width() < 922) {
		   toogle_menu();
		  } 
	  
}

var node_collors = new Array();
function add_node_collors()
{
	<?php 
    $result = mysqli_query($link, "SELECT * FROM `RefletorStations` ");

    // Numeric array

    // Associative array
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        if($row["Callsign"] == null)
        {
            $row["Callsign"]="nodata";
        }
        ?>
        node_collors["<?php echo $row["Callsign"]?>"]= new Array();
        node_collors["<?php echo $row["Callsign"]?>"]["id"] ="<?php echo $row["Callsign"]?>";
        node_collors["<?php echo $row["Callsign"]?>"]["color"] = "<?php echo $row["Collor"]?>";      
            		          
    <?php }?>

}

function player_move()
{
	var sidebar = $( "#wrapper" ).hasClass( "toggled" );


	var sidebar_size = $(  "#sidebar-wrapper").width();
	console.log(sidebar_size);
	$('#Player_bar').css('margin-left', sidebar_size);
		


	
	
}


function login_form()
{
	var login = $('#login').val();
	var password = $('#password2').val();
	
	$.post( "login.php", { login: login, password: password })
	  .done(function( data ) {
		  if(data == "true ")
		  {
			  
			  location.reload(); 
		  }
		  else
	    	alert( "<?php echo _("Wrong username or password")?>" );

		  

	  });
	
}
var loop_livelog =0;
var current_offset = 0
var log_size = 500;
var filter_log=""

function offset_log(offset)
{
	loop_livelog=0;
	console.log(offset);
	var serch_string = $("#logserch").val();
	current_offset= offset;
	$.get( "log.php", { offset: offset,search: serch_string,size: log_size,filter: filter_log }, function( data ) {
		  $( "#logdiv1" ).html( data );

		});
	return false;
}
function get_log_filter()
{
	
	var checked = []
	filter_log="";
	$("input[name='Log_filter_checkbox[]']:checked").each(function ()
	{
	    console.log(parseInt($(this).val()));

	    filter_log =filter_log+ $(this).val() +",";
	});
	offset_log(0)
	
	
}

function offset_log_neg()
{
	if(current_offset >= 0)
	{
		var new_page  = parseFloat(current_offset)-(1*log_size);
		if(new_page>=0)
		{
			offset_log(new_page);
		}
		
	}
}

function offset_log_add()
{

	
	var new_page  = (parseFloat(current_offset)+(1*log_size));
	offset_log(new_page);
	
}
function change_log_size(size)
{
	log_size=size;
	offset_log(0);
	
	
}



function load_live_log(value)
{
	if(value == true)
	{
		loop_livelog=1;
		live_log();
	
	}
	else
	{
		loop_livelog=0;
	}	
}
function live_log()
{
	if(loop_livelog ==1)
	{
		if (document.getElementById('logserch')) {
	
    		var serch_string = $("#logserch").val();
    		$.get( "log.php", { offset: current_offset,search: serch_string,only_table:1,size: log_size,filter: filter_log }, function( data ) {
    			  $( "#log_table" ).html( data );
    				setTimeout(function(){ live_log()}, 2000);
    
    			});
    
    	
		}
		
	
	}
	
}

function PrintElem(elem,text)
{
    var mywindow = window.open('', 'PRINT', 'height=600,width=800');

    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
    mywindow.document.write('<link rel="stylesheet" media="print" href="lib/css/bootstrap.min.css">');
    mywindow.document.write('<link rel="stylesheet" href="lib/css/bootstrap.min.css">');

    mywindow.document.write('</head><body >');
    mywindow.document.write('<h1>' + text  + '</h1>');
    mywindow.document.write(document.getElementById(elem).innerHTML);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/
    
    mywindow.addEventListener('load', function () {
        mywindow.print();
        mywindow.close();
    	})



    return true;
}
function stripHtml(html)
{
   var tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
}



function show_station_cover(ident,stationid)
{

	console.log(ident);


  var id2 =	stationid.slice(4)
	console.log(id2);

	 
	//peter
	
	remove_covige();
	
	coverigeGroup = new ol.layer.Group({
            layers: [],
            name: 'coverige'
        });
	map.addLayer(this.coverigeGroup);


	        
	<?php

			$result = mysqli_query($link, "SELECT * FROM `covrige` ");

			// Numeric array

			// Associative array
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			    
			    echo 'if(id2 =="'.$row['Name'].'"){';
			    
			    echo "
                " . $row["Radiomobilestring"] . "
    
                
                return 0;}
                ";
			    
			    
			}
			
			
			
			
			$result = mysqli_query($link, "SELECT * FROM `covrige` ");
			
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			    
			    echo 'if(ident =="'.$row['Name'].'"){';
	
			    echo "
                " . $row["Radiomobilestring"] . "
                        

                return 0;
                }";
                
	
			}
			
	
			
			

			?>


	
}
var message_toast_id =0;
function create_message_toast(message,title,type,color,hide)
{
	// multilne template
    var html = `
    <div  class="toast fade show"  role="alert" aria-live="assertive" data-delay="30000" aria-atomic="true"  id="message_show_id_%idnr%" style="min-width=800px !important;">
        
    <div class="toast-header toast_dash_header"  >
    <svg class=" rounded mr-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg"
        preserveAspectRatio="xMidYMid slice" focusable="false" role="img">
        <rect fill="#007aff" width="100%" height="100%" /></svg>
        
      <strong class="mr-auto"> %title%</strong>
      <small>%time%</small>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
        <div class="toast-body" style="min-width=500px !important; ">
        <div style="width:500px;"></div>
        
          %text%
        </div>
    </div>
    </div>
    `;
    
   

    html =html.replace("%idnr%", message_toast_id);
    //html =html.replace("%text%", stripHtml(message));
    html =html.replace("%text%", (message));
    html =html.replace("%title%", title);
    html =html.replace("%time%", time_NOW());
    html =html.replace("%hide%", hide);
    
    
 
    $('#message_container').append(html);
    $('#message_show_id_'+message_toast_id).toast('show');
    message_toast_id++;
    var x = document.getElementById("beep_message"); 
    x.load()
    x.play();  
}

function create_alert_toast(message,title,type,color,hide)
{
	// multilne template
    var html = `
    <div  class="toast fade show"  role="alert" aria-live="assertive" data-autohide="%hide%" data-delay="30000" aria-atomic="true"  id="message_show_id_%idnr%" style="min-width=800px !important;">
        
    <div class="toast-header" style="background-color: #ff3333 !important; color:white !important;" >
    <svg class=" rounded mr-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg"
        preserveAspectRatio="xMidYMid slice" focusable="false" role="img">
        <rect fill="#b30000" width="100%" height="100%" /></svg>
        
      <strong class="mr-auto"> %title%</strong>
      <small>%time%</small>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
        <div class="toast-body" style="min-width=500px !important; ">
        <div style="width:500px;"></div>
        
          %text%
        </div>
    </div>
    </div>
    `;
    
   

    html =html.replace("%idnr%", message_toast_id);
    //html =html.replace("%text%", stripHtml(message));
    html =html.replace("%text%", (message));
    html =html.replace("%title%", title);
    html =html.replace("%time%", time_NOW());
    html =html.replace("%hide%", hide);
    
    
 
    $('#message_container').append(html);
    $('#message_show_id_'+message_toast_id).toast('show');
    message_toast_id++;
    var x = document.getElementById("beep_message"); 
    x.load()
    x.play();  
}






function time_NOW() {
	
    var date = new Date();
    var aaaa = date.getFullYear();
    var gg = date.getDate();
    var mm = (date.getMonth() + 1);

    if (gg < 10)
        gg = "0" + gg;

    if (mm < 10)
        mm = "0" + mm;

    var cur_day = aaaa + "-" + mm + "-" + gg;

    var hours = date.getHours()
    var minutes = date.getMinutes()
    var seconds = date.getSeconds();

    if (hours < 10)
        hours = "0" + hours;

    if (minutes < 10)
        minutes = "0" + minutes;

    if (seconds < 10)
        seconds = "0" + seconds;

    return cur_day +" "+ hours + ":" + minutes + ":" + seconds;

}




//########################################################################## test ##################################################
function request_notification()
{
<?php 
if($_SESSION['loginid'] && USE_NODE_ADMIN_NOTIFICATION == 1)
	{
?>
	$.getJSON("request_notification.php", function(data){

		
		for(var n in data)
		{

			if(data[n].Active == "1")
			{
				create_alert_toast("Has Disconnect from reflektor ","Node "+data[n]["Callsign"],"","","true");
			}
			else
			{
				create_message_toast("Has connected to reflektor","Node "+data[n]["Callsign"],"","","true");
			}

			
	
		  
		}


		

		

	});

	
	instervalls = setTimeout(request_notification, 20000);
<?php }?>
}



//########################################################################## test ##################################################





</script>

<!-- Custom CSS -->
<link href="css/simple-sidebar.css" rel="stylesheet"> 


<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<!--must be here -->
<!--[if IE]><script language="JavaScript" type="text/JavaScript" src="EventSource.js"></script><![endif]-->
<script type="text/javascript" src="svx_stat.js"></script>

<style type="text/css">

/* Fixed navbar */
body {
    padding-top: 66px;
}
/* General sizing */
ul.dropdown-lr {
  width: 300px;
}

/* mobile fix */
@media (max-width: 768px) {
	.dropdown-lr h3 {
		color: #eee;
	}
	.dropdown-lr label {
		color: #eee;
	}
}

.topnav-right {
  float: right;
}


.label {
    display: inline;
    padding: .2em .6em .3em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: .25em;
}


.pull-Lable-right {
    float: right !important;
    vertical-align: middle;
}



</style>

</head>
<body>

<audio class="my_audio" id="beep_message"  controls="false""  preload="auto" style="display:none;">
    <source src="../beep.mp3" type="audio/mpeg">

</audio>








<div aria-live="polite" aria-atomic="true">

        <!-- Position it -->
      <div style="position: fixed; top: 0; right: 0; z-index:10; padding-top: 80px;opacity: 0 !important; padding-right: 20px;opacity: 1 !important;" id="message_container" >
    
    


    </div>

</div>


<!-- bg-dark -->


<!-- <nav class="navbar navbar-expand-sm navbar-inverse sidebar_collor  navbar-dark fixed-top text-white" >  -->




 <nav class="navbar navbar-expand-sm  sidebar_collor  navbar-dark fixed-top " >



<div class="container-fluid">

  <div>

    			
    <?php if(!USE_CUSTOM_SIDBAR_HEADER && USE_CUSTOM_SIDBAR_HEADER == 0)
    {?>
    
    
   <a class="navbar-brand" href="#">
    <img src="loggo.png" alt="Logo" style="width:40px;">

  </a>
   <a class="navbar-brand wite_font" href="#">
     SVX Portal
   </a>


    

<?php }else
{
    include "sideheader.php";
    
}?>

  <a href="#" class="sidebar-toggle " role="button" onclick="toogle_menu()">
                <span class="navbar-toggler-icon"></span>
</a>
  
  </div>




    
      <div class="topnav-right">
    
        <div id="navbar" class="">
    
          <ul class="nav  dropdown navbar1 flex-nowrap flex-row" >

 
                
            

                
            <?php if( $_SESSION["loginid"] ==""){?>

			<li class="nav-item dropdown">
			
            <a class="nav-link d-none d-xl-inline-flex d-lg-inline-flex" href="#register" onclick="" data-toggle="tab"><i class="far fa-plus-square"  style="color: #fff; padding-top:5px" ></i>&nbsp;<?php echo _('Register');?></a>
            
            <?php }?>
            <?php  if(HIDE_LANGUGE_BAR == 0){?>
    
          <li class="nav-item dropdown" style="color:#e1e3e9; ">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
   
              <?php return_flag($current_lagnuge)?>
              
    
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="background-color:#e1e3e9; padding-left:-20px;margin-left:-100px;box-shadow: 10px 10px 5px grey; text-align: left; " >

            <div style="width: 250px;"></div>
       		 <a href="#" class="dropdown-item"><?php echo _('Select your language')?></a>
       		     <div class="dropdown-divider"></div>
        	
            <a onclick="load_languge('en_UK')" class="dropdown-item table-primary" href="#"><img   src="images/flags/gb.svg" width="30px" alt="GB"> <?php echo _('English')?></a>
            
            <a onclick="load_languge('sv_SE')" class="dropdown-item table-secondary" href="#"><img src="images/flags/se.svg" width="30px" alt="Se"> <?php echo _('Swedish')?></a>
        
        
            <a onclick="load_languge('nb_NO')" class="dropdown-item table-primary" href="#"><img src="images/flags/no.svg" width="30px" alt="NO"> <?php echo _('Norwegian')?></a>
        
        	<a onclick="load_languge('uk_UA')" class="dropdown-item table-secondary" href="#"><img  src="images/flags/ua.svg" width="30px" alt="uk"> <?php echo _('Ukrainian')?></a>
        	
        	<a onclick="load_languge('it_IT')" class="dropdown-item table-primary" href="#"><img  src="images/flags/it.svg" width="30px" alt="it"> <?php echo _('Italian')?></a>
        	
        	<a onclick="load_languge('de_DE')" class="dropdown-item table-primary" href="#"><img  src="images/flags/de.svg" width="30px" alt="it"> <?php echo _('German')?></a>
        	
        	<a onclick="load_languge('fr_FR')" class="dropdown-item table-primary" href="#"><img  src="images/flags/fr.svg" width="30px" alt="it"> <?php echo _('French')?></a>
        	
        	<a  onclick="load_languge('tr_TR')" class="dropdown-item table-secondary" href="#"><img src="images/flags/tr.svg" width="30px" alt="tr_TR"> <?php echo _('Turkish')?></a>
        	
        	<a  onclick="load_languge('pl_PL')" class="dropdown-item table-secondary" href="#"><img src="images/flags/pl.svg" width="30px" alt="tr_TR"> <?php echo _('Polish')?></a>
        	
        	</div>
        	
    
          </li>
          <li class="nav-item dropdown"></li>
    	
    
    
        
    <?php }?>
       
       <?php if( $_SESSION["loginid"] !=""){?>

            
     	 <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            
            	<?php   if($_SESSION["User_image"] != null)
            	{
            	    echo '<img   src="'.$_SESSION["User_image"].'" width="25px" alt="GB" style="border-radius: 25%;">';
            	}
            	else
            	{        	
            	    ?>
            	<img   src="user.svg" width="30px" alt="GB"> 
            	 <?php }?>
                
                <span class="d-none d-xl-inline-flex d-lg-inline-flex">
          		<?php echo strtoupper($_SESSION["Username"]);?>
          		</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown" style="margin-left: -55px; box-shadow: 10px 10px 5px grey; background-color:#e1e3e9;">

<?php 
  /*
              <a class="dropdown-item" href="#">
              <i class="fa fa-key" aria-hidden="true"></i>
              <?php echo _('Password')?>
              
              </a>
*/
?>
                 <?php if( $_SESSION['is_admin'] > 0  ){?>
                
                <a class="dropdown-item" href="admin.php" id="">
                    <i class="fa fa-id-card" aria-hidden="true"></i>
                    <?php echo _('Admin interface')?> </a>

                <?php }?>
                

                <a class="dropdown-item" href="user_settings.php" id="">
                    <i class="fas fa-users" aria-hidden="true"></i>
                    <?php echo _('Account settings')?> </a>



              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="signout.php">
              <i class="fa fa-sign-out" aria-hidden="true"></i>
              <?php echo _('Sign out')?></a>
            </div>
          </li>
          
      
      
          
            
<?php }else{?>
            
   
            
            <li class="dropdown">
         	 <a href="#" class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-key" style="color: #fff;"></i> 
         	 <span class="d-none d-xl-inline-flex d-lg-inline-flex">
         	 	<?php echo _("Log In")?></a>	 
         	 </span>
              
              
              <ul class="dropdown-menu dropdown-lr animated slideInRight" role="menu" class="droptdown_popmenu" style="margin-left: -190px; background-color: #e1e3e9; box-shadow: 10px 10px 5px grey;">
                <div class="col-lg-12" style="padding: 25px; ">

                  <div class="text-center" style="color: black !important;">
                  
                    <h3><b><?php echo _("Log In")?></b></h3></div>
                    
							<form id="loginform" action="login.php" method="post" class="clearfix " style="display:inline-block;margin:0;padding:0;text-align:center" >
							  <div class="form-group">
							    <label for="login"><?php echo _('Username')?></label>	
								<input type="text" id="login" class="fadeIn second" name="login" placeholder="<?php echo _("Username")?>"><br />
							  </div>
							  <div class="form-group">
							     <label for="password2"><?php echo _('Password')?></label>	<br />
							  	<input type="password" id="password2" class="fadeIn third" name="password" placeholder="<?php echo _("password")?>">
						      </div>
						            
									 <input onclick="login_form()" type="button" class="fadeIn fourth" value="<?php echo _("Log In")?>">
									 
						 			<a class="underlineHover second" onclick="Reset_password()" href="#"><?php echo _("Reset password")?></a>
									 			
									 			
							</form>
                  
                  
                </div>
              </ul>
            </li>
            
<?php }?>            

                
          
                
            
          </ul>
        </div>
     
    </div>
</div>

</nav>



<div  class="container-fluid ">


<!-- 	<div id="wrapper"> -->


	<div id="wrapper" class="">

		<!-- Sidebar -->
		<nav id="sidebar" class="  navbar-fixed-left">
			<div id="sidebar-wrapper" class="sidebar_collor_left" >

				    
		
				<ul class="nav flex-column nav-pills navbar1 navbar2" role="tablist">
					<li class="nav-item"><a class="nav-link active" href="#Reflector" onclick="hide_menu_click()"
						data-toggle="tab"><i class="fas fa-broadcast-tower"></i> <?php echo _("Reflector clients")?> 
						<span class="label pull-Lable-right bg-warning" style="padding-top: 5px !important;" id="menuNodeCount"></span>
						</a>
						</li>
						
						<?php if(HIDE_MONITOR_BAR == 0){?>
					<li class="nav-item"><a class="nav-link " href="#listen"  onclick="hide_menu_click();player_move();get_audio_date();"
						data-toggle="tab"><i class="fas fa-headphones-alt"></i> <?php echo _("Monitor")?> 
						
						<span class="label pull-Lable-right bg-info" style="padding-top: 5px !important;" id="menuaudioCount">0</span>
						</a></li>
						<?php }?>
					
					<li class="nav-item"><a class="nav-link " href="#stationinfor"  onclick="hide_menu_click()"
						data-toggle="tab"><i class="fas fa-info-circle"></i> <?php echo _("Station information");?> </a></li>				
					
					<li class="nav-item"><a class="nav-link" href="#Echolink"	data-toggle="tab" onclick="hide_menu_click()">  
					<i class="fas fa-terminal"></i> <?php echo _("System description")?></a></li>
					<li class="nav-item"><a class="nav-link" href="#Dictionary"  onclick="hide_menu_click()"
						data-toggle="tab"><i class="fas fa-book"></i> <?php echo _("Talkgroups")?></a></li>
					<li class="nav-item"><a class="nav-link" href="list_reciver.php"><i
							class="fas fa-broadcast-tower"></i> <?php echo _("List receiver")?></a></li>

					<li class="nav-item"><a class="nav-link" href="#Statistics" onclick="get_statistics();hide_menu_click();"  
						data-toggle="tab"><i class="fas fa-chart-bar"></i>  <?php echo _("Statistics")?></a></li>

				<li class="nav-item"><a class="nav-link" href="#Log"  onclick="hide_menu_click();Load_log()"
						data-toggle="tab"><i class="fas  fa-align-justify"></i> <?php echo _("Log")?></a></li>


				<li class="nav-item"><a class="nav-link" href="#Last_heard_page"  onclick="hide_menu_click();activate_last_heard();"
						data-toggle="tab"><i class="fas  fa-align-justify"></i> <?php echo _("Last heard")?></a></li>
						
						
						

					<li class="nav-item"><a class="nav-link" href="#Recivers2"
						onclick="load_reflector();hide_menu_click()" data-toggle="tab"><i
							class="fas fa-broadcast-tower"></i>  <?php echo _("Receivers")?></a></li>
							
							
	
						
						
					<li class="nav-item"><a class="nav-link" href="#Table_ctcss" onclick="hide_menu_click();Load_ctcss_mapping();"
						data-toggle="tab"><i class="fas fa-terminal"></i> <?php echo _("CTCSS map table")?></a></li>
						
						<?php if( $_SESSION['loginid'] >0 ){?>
						<li class="nav-item"><a class="nav-link" href="requset_reflector_login.php">
						<i class="fa fa-globe"></i> <?php echo _("My stations")?></a></li>					
						<?php }?>
						
						
						
					<li class="nav-item"><a class="nav-link" href="#map_repeater"
						onclick="hide_menu_click();setTimeout(function(){
		   map.updateSize();connect_reflector();
	   },300); "
						data-toggle="tab"><i class="fas fa-map-marked"></i> <?php echo _("Map")?></a></li>


<?php
/*
<li>
<select class="browser-default custom-select custom-select-lg mb-3" onchange="load_languge(this.value)">
  <option  selected value=""  data-content='<span class="flag-icon flag-icon-us" ></span> English'>- Select Language -</option>
  <option  value="en_UK"  data-content='<span class="flag-icon flag-icon-us" ></span> English'>English</option>
  <option  value="sv_SE"   data-content='<span class="flag-icon flag-icon-se" ></span> Swedish'><i class="flag-icon flag-icon-se"></i>Svenska</option>
  <option  value="nb_NO"data-content='<span class="flag-icon flag-icon-mx"></span> Norwegian'>Norsk</option>
</select>
</li>
*/
?>





				</ul>
<div class="fixed-bottom wite_font">
 <div class="row " style="margin-left:10px ">
			&copy; SA2BLV <?php echo date("Y"); ?>
</div>
	</div>
			</div>

			<!-- /#sidebar-wrapper -->


		
<div class="col-sm-12 col-md-12  ">    
    
    
    		<!-- Page Content -->
    		<div id="page-content-wrapper">
    
    			<div id="my-tab-content" class="tab-content">
    
    				<div class="tab-pane active" id="Reflector">
    


        
        <div class="row">

            <div class="col-xl-10 col-lg-8">
              <div class="card shadow mb-10">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between navbar-dark   text-white bg-dark">
                  <h6 class="m-0 font-weight-bold text-white"><?php echo _('Active Nodes')?></h6>
                  <div class="dropdown no-arrow ">
                    <a class="dropdown-toggle text-white" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400 "></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink" style="">
                      <div class="dropdown-header"><?php echo _('Action menu');?></div>
                          <a class="nav-link " href="#" id="navbarDropdownMenuLink" onclick="fnExcelexport('Reflektortable')" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            				 <i class="far fa-file-excel"></i>
              						<?php echo _('Export xls')?>
      					 </a>
          					 
				        	 <a class="nav-link " href="#" id="navbarDropdownMenuLink" onclick="PrintElem('Reflektortable_div','<?php echo _('Reflector clients')?>')" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  				 <i class="fas fa-print"></i>
             						 <?php echo _('Print')?>
          					 </a>
            
            


                    </div>
                  </div>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class=""><div class=""><div class=""><div class=""></div></div><div class=""><div class=""></div></div></div>
                    
                    	<div id="Reflektortable_div"> 
					<table id="Reflektortable" class="table table-sm">
					<thead>
					<tr class="dash_header"><th><?php echo _("Callsign")?></th><th class="text-nowrap"><?php echo _("TG#")?></th><th class="text-nowrap"><?php echo _("Is talker")?></th><th><?php echo _("Monitored TGs")?></th><th class="d-none d-md-table-cell text-nowrap" ><?php echo _("Start talk")?></th><th class="d-none d-md-table-cell text-nowrap" ><?php echo _("Talk time")?></th></tr>
					</thead>
					<tbody>
					</tbody>
					</table>
					
					                  <div class="mt-4 text-center small">
<!--                     <span class="mr-2"> -->
<!--                       <i class="fas fa-circle text-primary"></i> Direct -->
<!--                     </span> -->
<!--                     <span class="mr-2"> -->
<!--                       <i class="fas fa-circle text-success"></i> Social -->
<!--                     </span> -->
                    <span class="mr-2">
                      <i class="fas fa-circle text-info"></i> <?php echo _('Talker')?>
                    </span>
					<span class="mr-2">
                      <i class="fas fa-circle text-warning"></i> <?php echo _('Dropped')?>
         		 </span> 
         		 
         		 <span class="mr-2">
                      <i class="fas fa-circle" style="color: magenta;"></i> <?php echo _('Restricted')?>
         		 </span> 
         		 
         		 


                  </div>
                  
                  
                  
				</div>
                    
                    
                    
                  </div>
                </div>
              </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-2 col-lg-4">
              <div class="card shadow mb-2">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between  navbar-dark   text-white bg-dark">
                  <h6 class="m-0 font-weight-bold "><?php echo _('Active talkgroups')?></h6>
               
                </div>
                <!-- Card Body -->
                <div class="card-body">
                
                <table class="table table-sm" id="active_talgroup_table" style=" word-wrap: break-word;">
                	<thead>
                		<th>
                		 <?php echo _("TG")?>
                		</th>
                  		<th>
                		 <?php echo _("Nodes")?>
                		</th>              
                	</thead>
                	<tbody>
                	
                	</tbody>
                </table>

                </div>
              </div>
            </div>
            
            
            
            
            
          </div>






	</div>

				<div class="tab-pane " id="listen">
					<div class="row">
				<div class="col-md-7"> 
						<!-- 	<h1><?php //echo _("QSO Monitor")?></h1> -->
				
							
			<div> 
					
					<div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between text-white bg-dark">
                  <h6 class="m-0 font-weight-bold "><?php echo _('Player')?></h6>
 
                </div>
                <!-- Card Body -->
                <div class="card-body">
                  <div class=""><div class=""><div class=""><div class=""></div></div><div class=""><div class=""></div></div></div>
      
        

							
				
							<div id="jp_container_N" class="jp-video jp-video-270p"
								role="application" aria-label="media player" style="width: 100%">
								<div class="jp-video-play">
									<button class="jp-video-play-icon" role="button" tabindex="0">play</button>
								</div>



								<div class="jp-type-playlist" lang="" > 
									<div id="jquery_jplayer_N" class="jp-jplayer"></div>
									<div id="Player_bar" class="jp-gui fixed-bottom">

										<div class="jp-interface">
											<div class="jp-progress">
												<div class="jp-seek-bar">
													<div class="jp-play-bar"></div>
												</div>
											</div>
											<div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
											<div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
											<div class="jp-controls-holder">
												<div class="jp-controls">
													<button class="jp-previous" role="button" tabindex="0">previous</button>
													<button class="jp-play" role="button" tabindex="0">play</button>
													<button class="jp-next" role="button" tabindex="0">next</button>
													<button class="jp-stop" role="button" tabindex="0">stop</button>
												</div>
												<div class="jp-volume-controls">
													<button class="jp-mute" role="button" tabindex="0">mute</button>
													<button class="jp-volume-max" role="button" tabindex="0">max
														volume</button>
													<div class="jp-volume-bar">
														<div class="jp-volume-bar-value"></div>
													</div>
												</div>
												<div class="jp-toggles">
													<button class="jp-repeat" role="button" tabindex="0"><?php echo _("repeat")?></button>
													<button class="jp-shuffle" role="button" tabindex="0"><?php echo _("shuffle")?></button>
													<button class="jp-full-screen" role="button" tabindex="0"><?php echo _("full screen")?></button>
													
												</div>
												
											</div>
							
											<div class="jp-details">
												<div class="jp-title" aria-label="title">&nbsp;</div>
											</div>
										</div>
									</div>


									<div class="jp-no-solution">
										<span>Update Required</span> To play the media you will need
										to either update your browser to a recent version or update
										your <a href="http://get.adobe.com/flashplayer/"
											target="_blank">Flash plugin</a>.
									</div>
								</div>
								
								
															<div class="jp-playlist">
									<ul>
										<!-- The method Playlist.displayPlaylist() uses this unordered list -->
										<li>&nbsp;</li>
									</ul>
								</div>
								
							</div>
								
							</div>
<!-- End of containter player						 -->
		          </div>
                </div>
              </div>
 							<div style="height: 100px;"></div>
 
						
							
						</div>
						<div class="col-md-5">
						

    			
						
						<div class="card card_margin shadow mb-4">
    						<div class="card-header text-white bg-dark">
    						<i class="fab fa-forumbee"></i> <?php echo _('Live');?>
    						</div>
    						<div class="card-body bg-white">
    		
    							  <div class="form-group">
    							  
                                    <label for="Live_station"><?php echo _('Select channel');?></label>
                                    <select class="form-control" id="Live_station">
                                    
                                        <?php if($livelink!= ""){?>
                                        		<option value="<?php echo $livelink?>"><?php echo _('Default')." ". $default_tg_player?></option>
                                        <?php }?>
                                        
                                        <?php if($livelink_station)
                                         {
        
                                             
                                         foreach ($livelink_station as $key => $value) {
                                             
                                             echo '<option value="'.$value["URL"].'"><i class="fas fa-headphones-alt"></i>'.$value["Name"].'</option>';
                                           }
                                         }
                                           ?>
                                    
                                    
    
                                    </select>
    						  		</div>
    							<button type="button" onclick="listen_live()"
    								class="btn btn-outline-success my-2 my-sm-0"><?php echo _('Listen LIVE')?></button>
    							<button type="button" onclick="listen_live_external()"
    								class="btn btn-outline-success my-2 my-sm-0"><?php echo _('Open in mediaplayer')?></button>
    								
								</div>
							</div>
			
							<div class="card card_margin shadow mb-4">
							<div class="card-header text-white bg-dark">
    						<i class="far fa-calendar-alt"></i>
    							<?php echo _("Select date to listen")?>
    							</div>
    							
        							<div class="card-body bg-white">
        							<?php if($use_logein != null || USE_LOGIN == 1){?>
        								<p><?php echo _("to use player you must login")?> </p>
        							<?php }?>
        							<p>
        								<input type="text" id="datepicker"
        									value="<?php echo date("Y-m-d")?>"
        									onchange="get_audio_from_date(this.value)">
        							</p>
        							</div>
        					</div>		
        		
							<div class="card card_margin shadow mb-4">
    							<div class="card-header text-white bg-dark">
    								<i class="fas fa-broadcast-tower"></i>
    								<span" id="Stationid"><?php echo _('Signal')?></span>
    							</div>
    							<div class="card-body bg-white">
           						     <p class="card-text"><span id="signalpressent">0</span>% <?php echo _("Signal value from receiver")?>.</p>
           						     <hr />
        							
                   						<div id="Reciverbars_player"></div>
        						
    							</div>
				
							</div>
							<div style="height: 100px;"></div>
						
			
	
	   

	<?php
	

	
	
	$sql_node ="SELECT Talktime, `Callsign`, `Talkgroup` FROM RefletorNodeLOG WHERE `Type` = '1' AND `Active` ='0'";

	
	 
	function secondsToDHMS($seconds) {
	    $s = (int)$seconds;
	    if($s >0)
	       return sprintf('%d:%02d:%02d:%02d', $s/86400, $s/3600%24, $s/60%60, $s%60);
	    else
	        return "0:00:00:00";
	    
	}
	
	//$sqlref = $link->query($sql_node);
	$timesum_node =array();
	
	$timesum =array();
	

// 	while($row = $sqlref->fetch_assoc()) {
// 	    $timesum_node[$row["Callsign"]] =$timesum_node[$row["Callsign"]]+ $row["Talktime"];
// 	    $timesum[$row["Talkgroup"]] =$timesum[$row["Talkgroup"]] +$row["Talktime"];
	    
// 	}
	


	
	
	

$result = mysqli_query($link, "SELECT * FROM `RefletorStations` where Callsign != '' ");

// Numeric array



?>
	</tr>
								</tbody>
							</table>


						</div>



					</div>


				</div>
				<div class="tab-pane" id="Echolink">
<!-- 					<h1>Commands</h1> -->
  <div class="">
			<?php
if($usefile != null)
{
    $myfile = fopen("echolink.txt", "r") or die("Unable to fla file!");
    echo utf8_encode(fread($myfile, filesize("echolink.txt")));
    fclose($myfile);
}else
{
    if(USE_EXTERNAL_URL != 0 )
    {
     ini_set('default_socket_timeout', 20); // 900 Seconds = 15 Minutes
     if($Use_translate_on_info_page  == true)
     {      
         $data =file_get_contents(translate_folder_page(iframe_documentation_url));
         
         if($data== "")
         {
             $data = file_get_contents(iframe_documentation_url);
         }
     }
         
      else
      {
          $data = file_get_contents(iframe_documentation_url);
          
      }
 

    echo $data;
    }
    
    
?>

<?php }?>	
	</div>
				</div>
				<div class="tab-pane" id="Recivers2">
					<div class="container">
						<nav
							class="navbar navbar-expand-lg navbar-light bg-light navbar navbar-light bg-light justify-content-between">
							<?php echo _('Filter')?>:<select id="selects" class="w-25"
								onchange="update_filter(this.value)">
								<option value="">-- <?php echo _("All")?> --</option>
							</select>
							
							<a href="#"></a>
						</nav>
					</div>
					<div id="holder" class="container"></div>

				</div>

				<div class="tab-pane" id="Recivers">
				
				</div>



				<div class="tab-pane" id="Recivers2"></div>
				<div class="tab-pane" id="Dictionary">

    
    
    
   	 <div class="card shadow mb-4">
         
                
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between navbar-dark   text-white bg-dark">
                  <h6 class="m-0 font-weight-bold text-white"><?php echo _("Talkgroups")?></h6>
                  <div class="dropdown no-arrow ">
                    <a class="dropdown-toggle text-white" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400 "></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink" style="">
                      <div class="dropdown-header"><?php echo _('Action menu')?></div>
                          <a class="nav-link  " href="#" id="navbarDropdownMenuLink" onclick="fnExcelexport('dictornay_taklgroup_data')" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            				 <i class="far fa-file-excel"></i>
              						Export xls      					 </a>
          					 
       					 <a class="nav-link  " href="#" id="navbarDropdownMenuLink" onclick="PrintElem('dictornay_taklgroup_print','<?php echo _('Talkgroups')?>')" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  				 <i class="fas fa-print"></i>
             						 <?php echo _('Print') ?>          					 </a>
             						 
             			<a class="nav-link  " href="other/anytone/analog_adressbook.php" target="_blank aria-haspopup="true" aria-expanded="false">
                  				 <i class="fa fa-users"></i>
             						 <?php echo _('Export Antyone') ?>          					 </a>
             						 
            
            


                    </div>
                  </div>
                </div>
                
                
                
                
                <div class="">
                  <div class="card-body"> 
       
    
    
    
    
    
				<div id="dictornay_taklgroup_print">
					<table class="table table-striped table-sm" id="dictornay_taklgroup_data">
						<thead class="dash_header">
							<tr>
								<th><?php echo _("TG#")?></th>
								<th><?php echo _("Talkgroup Name")?></th>
								<th  class="d-none d-md-table-cell"><?php echo _("Callsign")?></th>
								<th class="d-none  d-md-table-cell"><?php echo _("Last active")?></th>
								<th  class="d-none d-md-table-cell"><?php echo _("Color")?></th>
							</tr>
						</thead>
						<tbody>
	   
<?php 
	
	// move earlier in code to increse speed 
	
	
	




	
mysqli_set_charset($link,"utf8");


/*
$result1 = mysqli_query($link, "SELECT `Id`,`Talkgroup`,`Time`,`Callsign` FROM `RefletorNodeLOG` WHERE

`Id` IN (
    
    SELECT MAX(RefletorNodeLOG.Id)
    FROM RefletorNodeLOG
    GROUP BY RefletorNodeLOG.`Talkgroup`
    );");
*/


/*
$last_active_array = array();
while ($row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC)) {

    $last_active_array[$row1['Talkgroup']]["Callsign"] =$row1["Callsign"];
    $last_active_array[$row1['Talkgroup']]["Time"] =$row1["Time"];
};

*/
?>



<?php 
$result = mysqli_query($link, "SELECT * FROM `Talkgroup` ORDER BY `Talkgroup`.`TG` ASC");




// Numeric array

// Associative array
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
{
    echo "<tr>";
    echo "<td>" . $row["TG"] . "</td>";
    echo "<td>" . $row["TXT"] . "</td>";

    $result1 = mysqli_query($link, "SELECT Callsign,Time FROM `RefletorNodeLOG` WHERE `Talkgroup` ='".$row["TG"]."' ORDER BY `RefletorNodeLOG`.`Id` DESC limit 1");
    $row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC);

    
    echo "<td  class='d-none d-md-table-cell'><spawn id=\"Last_".$row["TG"]."\">" . $row1["Callsign"] . "</spawn></td>";
    echo "<td class='d-none d-md-table-cell' >".$row1["Time"]."</td>";
  //  echo "<td>".secondsToDHMS($timesum[ $row["TG"]])."</td>";
    echo "<td class='d-none d-md-table-cell'>".'<div style="border:2px solid black; width: 25px; height :25px;  background-color:'.$row["Collor"].' ">'."</td>";
    
    echo "</tr>";
}

?>

						</tbody>
					</table>
</div>
         </div>
              </div>
    

					</div>



					   
				</div>

				<div class="tab-pane " id="Log">

					
					<div id="logdiv1" class="col-xs-6">
					<?php //include_once 'log.php';?>
					</div>

				</div>


				<div class="tab-pane" id="map_repeater">
					<div class="row">
					<div class="col">
						<nav id="mapmenue"
							class="navbar navbar-expand-lg navbar-light bg-light navbar navbar-light bg-light ">

							<ul class="navbar-nav">

								<li class="nav-item dropdown"><a
									class="nav-link dropdown-toggle" href="#"
									id="navbarDropdownMenuLink" data-toggle="dropdown"
									aria-haspopup="true" aria-expanded="false"><i class="fas fa-broadcast-tower"></i> <?php echo _("Coverage")?> </a>
									<div class="dropdown-menu"
										aria-labelledby="navbarDropdownMenuLink">
										<a class="dropdown-item" onclick="show_covige()" href="#"><i class="fas fa-asterisk"></i> <?php echo _("Show")?></a>
										<a class="dropdown-item" onclick="open_select_map()" href="#"><i class="fas fa-asterisk"></i> <?php echo _("Select")?></a>
										<a class="dropdown-item" onclick="remove_covige()" href="#"><i class="fas fa-asterisk"></i> <?php echo _("Remove")?></a>

									</div></li>
								<li class="nav-item dropdown"><a
									class="nav-link dropdown-toggle" href="#"
									id="navbarDropdownMenuLink" data-toggle="dropdown"
									aria-haspopup="true" aria-expanded="false"><i class="fas fa-bolt"></i> <?php echo _("Actions")?> </a>
									<div class="dropdown-menu"
										aria-labelledby="navbarDropdownMenuLink">
          

		  
	<?php

$result = mysqli_query($link, "SELECT * FROM `Filter`");

// Numeric array

// Associative array
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    // echo '<a class="dropdown-item" onclick="prosess_json_filter(\'' . $row['JSON'] . '\',\'' . $row['Filter'] . '\')" href="#">' . $row['Namn'] . '</a>';
}
?>
		  
		  <a class="dropdown-item" onclick="map.overlays_.clear();" href="#"><i class="fas fa-minus-circle"></i> <?php echo _("Remove bars")?> </a> <a class="dropdown-item"
											onclick="vectorSource.clear();Barsource.clear();map.overlays_.clear();"
											href="#"><i class="fas fa-minus-circle"></i> <?php echo _("Remove ALL stations")?></a> <a class="dropdown-item"
											onclick="prosess_json_reflecktor();" href="#"><i class="fas fa-asterisk"></i> <?php echo _("Show Receivers")?></a>
											
											
											<?php 
											
											/*
											
											<a class="nav-link" href="#"
											
											
									onclick="toogle_AutoFollow()" id="Autofollow_text"
									data-toggle="dropdown" aria-haspopup="true"
									aria-expanded="false"><i class="fas fa-asterisk"></i> <?php echo _("Toggle AutoFollow")?> </a>
									
									
																				<a class="nav-link" href="#"
									onclick="toogle_AutoFollow()" id="Autofollow_text"
									data-toggle="dropdown" aria-haspopup="true"
									aria-expanded="false"><i class="fas fa-asterisk"></i> <?php echo _("Toggle AutoFollow")?> </a>
									
										*/
									?>
									
									<a class="nav-link" href="#"
									onclick="set_disable_text(1)" id="Autofollow_text"
									data-toggle="dropdown" aria-haspopup="true"
									aria-expanded="false"><i class="fas fa-asterisk"></i> <?php echo _("Remove labels")?> 
									</a>
									
								
									
									
									
									<a class="nav-link" href="#"
									onclick="set_disable_text(0)" id="Autofollow_text"
									data-toggle="dropdown" aria-haspopup="true"
									aria-expanded="false"><i class="fas fa-asterisk"></i> <?php echo _("Show labels")?> 
									</a>
									
									
									<a class="nav-link" href="#"
									onclick="connect_reflector()" id="Autofollow_text"
									data-toggle="dropdown" aria-haspopup="true"
									aria-expanded="false"><i class="fas fa-asterisk"></i> <?php echo _("Reload Map")?></a>
									
		

									</div></li>
						
			


							</ul>





						</nav>
						
					</div>
					</div>


			
						<div class="col-sm-12 fill" style="">
						
								<div class="map" style="" id="map"></div>

						</div>
						
						

				

					<!-- Modal -->
					<div class="modal fade" id="Showstation_info" tabindex="-1"
						role="dialog" aria-labelledby="exampleModalLabel"
						aria-hidden="true">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">
										<?php echo _("Station info for")?> <span id="stname"></span>
									</h5>


									<div id="station_txt"></div>
									<button type="button" class="close" data-dismiss="modal"
										aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class="row">

										<div class="col-md-4">
											<ul class="list-group">
												
												<li class="list-group-item"><?php echo _("Location")?></li>
												<li class="list-group-item"><?php echo _("Talkgroups")?></li>
												<li class="list-group-item"><?php echo _("Version")?></li>
												<li class="list-group-item"><?php echo _("CurrentTG")?></li>
												<li class="list-group-item"><?php echo _("Sysop")?></li>

											</ul>
										</div>
										<div class="col-md-8">
											<ul class="list-group">
												
												<li class="list-group-item" id="nodeLocation_stn"></li>
												<li class="list-group-item" id="stn_tg"></li>
												<li class="list-group-item" id="swVer_tg"></li>
												<li class="list-group-item" id="tg_tg"></li>
												<li class="list-group-item" id="sysop_tg"></li>
											</ul>
										</div>
										<p>&nbsp;</p>

							

											<table class="table">
												<thead class="table-dark">
													<tr>
														<th scope="col"><?php echo _("RX Name")?></th>
														<th scope="col"><?php echo _("Location")?></th>
														<th scope="col"><?php echo _("SqlType")?></th>
														<th scope="col"><?php echo _("Frequency")?></th>
														<th scope="col"><?php echo _("Enable")?></th>




													</tr>
												</thead>
												<tbody id="optable_stn">
												</tbody>

											</table>


									</div>
									<button type="button" class="btn btn-secondary"
										data-dismiss="modal"><?php echo _('Close')?></button>
									<button type="button" class="btn btn-secondary" id="Show_covige_button"
										><?php echo _('Show covreage')?></button>

								
								
								<?php 
								if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 )
								{
								    
								
								?>
								
								<button type="button" class="btn btn-secondary" id="Show_radiomobile_button">
									<?php echo _('Radiomobile')?>
								</button>

						
								
								
								<?php }?>
								
								</div>							
								
								
							</div>
						</div>
					</div>



					<div id="overlay"
						style="background-color: white; border-radius: 10px; border: 1px solid black; padding:">
						<script>


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

		
	
        var map = new ol.Map({
          target: 'map',
          layers: [layer,vectorLayer,Barlayaer],
          view: view
        })
		
	var currZoom = map.getView().getZoom();
	map.on('moveend', function(e) {
	  var newZoom = map.getView().getZoom();
	  if (currZoom != newZoom) {
//		console.log('zoom end, new zoom: ' + newZoom);
		currZoom = newZoom;
		if(currZoom <= 8)
		{
			map.overlayContainer_.hidden = true;
			Barlayaer.setVisible(false);
		}
		else
		{
			map.overlayContainer_.hidden = false;
			Barlayaer.setVisible(1);
		}
	  }
});

	map.on('click', function(evt) {
		  var feature = map.forEachFeatureAtPixel(evt.pixel,
		    function(feature) {
		      return feature;
		    });
		  if (feature) {
		    var coordinates = feature.getGeometry().getCoordinates();
		 	
			 var idn = feature.id_;
			 console.log(idn);
			 if(idn.startsWith('aa'))
				 var identity =station_identifire[idn.slice(2)];
			 else
		     	var identity =station_identifire[idn.slice(4)];
			// Get the <a> element with id="myAnchor"
			 var x = document.getElementById("Show_covige_button");  
			 x.setAttribute("onclick", "show_station_cover('"+identity+"','"+idn+"')");

			 <?Php
			 if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 )
			 {    ?>
			 var x = document.getElementById("Show_radiomobile_button");  

			 
			 x.setAttribute("onclick", " window.open('radiomobile_front.php?stationid="+idn.slice(4)+"&ident="+identity+"',\"mywindow\",\"menubar=1,resizable=1,width=800,height=600\");");
            
			 <?php }?>


			 

		     	
		     show_station_information(identity);


		}

		});

  

			
		
		
var ico = [
"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAStSURBVGhD7VlNbBNHFA4HekQgtUdAgmRtovCjBFBROVS9tBVCIBUubREguKJeuPHXqpU4VG1pK7U4a8chRUBD2Fk7zo8gFIQh4k8KhNImggYS56cJaiAJFGhph/c2b1cz6yGyya5tJH/SJ9l+7833zfrtzHhdUkQRReQWPyxOzNE14+Owxr7XAybTNbPdIrzGz8IB8yPMofTCg67F3gprZiuY/RfM8impmf9gbnUw9iaV5x/hRQ3z9QAzlIYzINbWLGRzabj8IKIZq6FlRlQGs+QwfqM0bG4RDsQ2Qu8/VZh6SbIn1QFzAw2fG+hlxkq4Fx6pDU2Dk2MuJxl/ESn9+Q0QS0kGgHUrmqT3mfAFNamQFn+d5PwDtNN3bvGW7e18tHuMG+vPSJ9PRczFmpZt7WkxXWMHSM4fHCw1FlpLpyB6Ys1pfr9nnE8MPOIj10f5kdWtkikVMQdzsQZrG95vk+J476EWyXoPvFKiYKQ8xgcujFiGrIl0jvLj78mmVDz2zkl+79f7Tl3/+WEeWRSTcmA1/IZkvQdcqTuiWNuOS44ZvLKZTMKm+cFZPtb30KnHseQc1kOy3qK6jC2ThUx+p7XfMXJx/w0phqytbORNm5IW8bU7fi3U7dT/0dSfFoe9ZQnJewfr/CSI1C1v4uN0RcdTD/nhVc2SCWPtL1ar2UbxnsDPxBy8N+z4WN8EP1SZkOIRjW0jee8AX/VOUcRYd8Yx0Q/3iRhDYt/bcZup5LCcFzT5X10PnDhbJ08U9pUvSN47wES+FkVwybUN3I6nJAN1VQkn5ibGxFxxws1bL0gxmMi3JO8dYCKfiSKJD885Bu62DUoGamA1e0BLskj8DGNi7tDle068EcYUY3Cg/JTkvQMsh1tFEVyhbAPYHrgUi/ErB35z4javfHVTyokubeRjvRNOvP7dU1JcD8Y2k7x3sDZDQQSNi/3tvpoYT+7u4HdPDVhM7upIm6zYnrivuPeSSFnDApL3FtCzt0ShG9HbjpFeaC+30amIuX1n/3TqOyO3pDhsvl0k6z3geLJPFMP2wqXXNtPxY5dkZipe/vKmU4fLeFpbaWwPyXoP/KpB5D9RUNzUkM1bXCuPgrhBijXXDnbLOfBzOVQen0ey/gBWL1MUjS5p5L2nBy1Dvx/tsfYGMa4k5GAu1mBLRivirhxWT3L+wXrIIIma1q6OPV4Lk3LHXkTMxRr3icBi0FhFcv4CrlhLmrhHhL0jQTL+oyYYrwTB/1VGpkMcEw+nJJMbwA15XGVmmjxGw+cOkVKzHHb7ZwozL8fJB3sBGj63AHF8BKo2li39OCBmiuh8NhtMDKeZypKw+Q39VNo8i4bND/BgpzKXFTW2iYbLH3gJnwH3SlJpMDOewzFouPyiJsAWux8TZUJ87IO1NExhwP3DKxPCRPZSeeGgvrz+NTDWqTKsIuaGqkIzqbywgA+3M9pbYM/Qg6yKygoT0GL7leYFwnL7OaUXLrDFwGyH27xDzbyKOZRe2AgHT1TAAfCxexLQdn/j0YbSXg2A8U/cE4G220HhVweTGyVL2JPA1wWz8WUL/OcJbuwBZE7+hfIT8E28jaS3RRRRRF5QUvIc6Euk3wKNfCMAAAAASUVORK5CYII="
,
"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACIAAAAuCAMAAAB6WzuLAAADAFBMVEUReP4ReP4ReP4QeP4Pd/4Ndv4LdP4Jc/4Gcf4DcP4CcP4Bb/4Ab/4Ab/4Ab/4Abv4Abv4Abv4Abv4Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0AavYAY+sAXOAAWNgAVNEAUcwAT8kATscATcUATMQATMQAS8MAS8MAS8MAS8MAS8MAS8MAS8MAS8MAS8MATMMATMMATMIATcIAUMABVb4BWLwBW7oBXrcCXrICWqsBU6ABSpQBQ4sBQokBQYgCQIcCP4UEPIIFOH0JMnMLLW0NL28PMnIUNnUZOnghQHwnRYAqSIIuS4QyToc2U4o+W5BEYpZKaZpPbp5WdKJceqZng6xvi7BwjbByjrB3kbGBl7KGmbKKmrOMm7ONm7OOm7SPnLWQnbWRnraVormZp7ydrL+hssOnuciqvcuwwc+3x9S/zdrF09/J1uLL1+PN2eTR2+bV3ejZ3+nd4evg4+vj5Ozl5u3l5+3m6e/k6/Dh7vLZ8PTP9Pe7+fu2+vy1+/21+/21+/21+/21+/21+/21+/23+/26+/2//P3H/P3b/f32/v3+//3///z///z///z///v///z///3///7///7////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////1DM8mAAABAHRSTlP/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////AAAAAAAAAAAAAAAA//////////////////8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAApx8BhgAAAAlwSFlzAAAOwwAADsMBx2+oZAAAAVxJREFUOI2F1L2KwzAMAGA/wC2d825+gI5atIUrNORM4Qqe0rvFlGRyZo/lIA/iV7n8R5adVFvgQ7YsKcK/DUE/7EN/qVJV9Q5pqwuCHAIgV3WCaJQyW0OiahgxBWRhSNQBqVBmUYAi5IEx2MxAajwnSQZ6ISpxyhRoJ/LLb0rurCZypUmEc46lEb6mST5cSIY0oqM3OTlGsnzIctnKGXOEBBsvXtubOBcTeHhhgYuASO2FeUMUI+IUkZsXDSFzSTxLG7QwIvDTF50fE9uToIkR+RyeroEDAvexjfkRaUdSwS6R5TQMtCZG4DlP3bfcIWOSkZBOhgTMugEa0kSRPbomCdZ01VITLumqpfcEW0o6G+9jP2/hn+EerX3RMfLHj0IT/V/YYg/944RVVfgE8YoYfCYJqRy0TxOzVL48WkzWyrdqIjL3E/Rrn/ib5MdExJfyXNpjYnVh/DFJxD+xZNmYMqLlMgAAAABJRU5ErkJggg=="
,
"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACIAAAAuCAMAAAB6WzuLAAADAFBMVEUSMm4SMW0SMGwQNHMOO30LPoIIP4UGQIYHQIcGQosGRpYFSqEET68EULcEUboDULwCT74BTcEATMIAS8MAS8MAS8MATMQATMQATsYAUMgAU8wAWNIAYt0AbeoAc/IAefgAff4Afv4Afv4Bf/4Aff4Ae/4Aef4Ad/4Adf4Ac/4Acf4Ab/4Abv4Abv4Abv4Abv4Abv4Abv4Abv4Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0DbvwGbvsIbvkLbvgNb/cQb/YUb/UXb/Mab/Idb/Efb/Aib+8kb+4mcO0ocOwpcewqcesrcesrcessceotceoucekucekvcekvcegwcegxceYzcuQ1cuE3c985c91EdtJKeMxOechResVXfMFcfr1hgbpohLdrhbRshrRuh7NviLRwiLNwiLNxibN1i7R8jraDkbeHk7iKlbmLlbmNl7qOmLqPmbqRnLuTn7uVo7yYqLyaqr2crL6fr7+issCltcKnt8Osu8ewvsm2w868x9LBzNfFz9rI0dzK09/M1eHO2OTP2+fQ3OnQ3enP3urN4OzK4u/F5/O+7fm58f238/+38/+38/+38/+38/+38/+38/+38/+48//C8fzM8PnY7/bj7vPp7fLu7fDx7fDy7vH08PL28/X39fb6+Pj6+fn7+vr9/Pv+/vz///z///z///z///v///z9//38//78//7+//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////9YAKYnAAABAHRSTlP/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////AAD//////////////////////wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAxqLkTAAAAAlwSFlzAAAOwwAADsMBx2+oZAAAAXdJREFUOI2F1D1uwyAUAGCEZOkNzcYNcgOfghtlZ/HAirI4VVRVlRgsTz5B2DpZ8h04RwGHf+y+Lcqn9wM8I/1voPSHeoqBD3ycDogaPzAQGwAdnxpEYEJoCIL5XBDJgOZBsMjIiAmtAnhCnrgG0Vgy4UuTUBCe8EaVPbDayXfs9Lq4iD3znTCSAxO3NA3Sk0/SL0tlbBq08izJ7S19ms5m6fw4/q9rSvCs0ebPpPcF+pTAp0avOE9qw0xCI1mRrJDptyZ5u2TQaC5JdXhI4YYIR0fhyw59JigoQ7JLLAVF9ujSZrJhXJKHu8buWFDYHBnhUJiRHfEz9WUfJol8v7o7ia36iEkc2W+yrwn8hA0QQNM3FwhP9ojRVuApXTVoCJKuWntP3IYEsr7qfTTvLf8yPMpShK0F+S1LYVl9X4rFtvdXkmIqphtEc2iVyT+HsR0Quk2kn9wfWk3C5GmZguz3acpsx0QPpCxTEWMugzonSjCpz0kj/gCNqVLifMYqzwAAAABJRU5ErkJggg=="
,
"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACIAAAAuCAMAAAB6WzuLAAADAFBMVEUTMWsRLGoONHANPn8GQIUKQIcMQ4oNSZMLT54KVaoIWbgGWLsDU74CTsEBTMMATMQATsgAUs4AVtUAYukAbPoAbv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0Abv0EbvYIbfAMbesPbOcXbuAncNE4dMJCdrlLeLNReq9WfK5cf61jg65phatthqluh6xvi7JwjLJxjbJ2kLJ6lLKAl7KGmrKKnLKMnLOOm7OPm7OPm7SQm7WUnreXormaprudrb+fuMmgxdOfyNmezd+c0+Sa2OmZ2eqZ2eqZ2eqZ2eqZ2eqZ2eqZ2eqZ2eqZ2eqZ2eqa2ema2Oie1+Si1uKq1N6w092z0ty40du90dvB0tzF093J097L1eDO1uHR1+TU2ubX3enY3urZ3+rf4evj5O3m5u3p5+7s6u7v7PDx7fDy7vDz8PD18/P49vb7+vj8+/n9/fn9/fn+/vn+/vn///n///j///j///f///j///v///z///7////////////////9///8///+//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////92dZ6MAAABAHRSTlP///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQAdNaAAAAAlwSFlzAAAOwwAADsMBx2+oZAAAAVxJREFUOI2F1L1uhDAMAOBYWFXFkLfhVTJRZekNWWDjCbocC+pwYmBn6cKr+I0KgYDzx2U6dJ8cO44j6O0S/GNszZeudTNkyNR8AsptIRZ6SBADUlbnknChg/QKK39JMB5pwEXous4h1Iy0UF0iMhsZoEySCo0j+srTJxWMO/lBXzAj9U6UzBIbRtDAyo3IFkbMYSa+KbYoH2UkmIFfEgvEgCFsSbww8f+lpCHRY7CFb9Z8QxJ+yJrE8D7KBLe5bOlSkSzIlYTjSq6jS52LCBoQC3zYNhY3PULbRmpuLkO9X4YJsgSfx637zt06G8SSBTIE+3MCDKaJZnOkeJjzNwx81MJZtJnwUfPm5Fxi4mR+iUisDfRfhkc09moOyF+4FfTR+8IG227ziJ+goCpFCUKaGXgmCascDaVJ73rlDi0mZ+VXNRE5+olmyROqZbhNRFZT1uM9GY3q6Z4k1j81qd3gAk1yDQAAAABJRU5ErkJggg=="
,
"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAA7EAAAOxAGVKw4bAAAKT2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AUkSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXXPues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgABeNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAtAGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dXLh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzABhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/phCJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhMWE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQAkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+IoUspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdpr+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZD5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61MbU2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllirSKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79up+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6VhlWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lOk06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7RyFDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3IveRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+BZ7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5pDoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5qPNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIsOpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQrAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1dT1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aXDm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3SPVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKaRptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfVP1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAADwCaVRYdFhNTDpjb20uYWRvYmUueG1wAAAAAAA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/Pgo8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjYtYzA2NyA3OS4xNTc3NDcsIDIwMTUvMDMvMzAtMjM6NDA6NDIgICAgICAgICI+CiAgIDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+CiAgICAgIDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiCiAgICAgICAgICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgICAgICAgICAgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iCiAgICAgICAgICAgIHhtbG5zOnN0RXZ0PSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VFdmVudCMiCiAgICAgICAgICAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIKICAgICAgICAgICAgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIgogICAgICAgICAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgICAgICAgICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iPgogICAgICAgICA8eG1wOkNyZWF0b3JUb29sPkFkb2JlIFBob3Rvc2hvcCBFbGVtZW50cyAxNC4wIChXaW5kb3dzKTwveG1wOkNyZWF0b3JUb29sPgogICAgICAgICA8eG1wOkNyZWF0ZURhdGU+MjAxOS0wMS0yNlQxMzozMToxMi0wNTowMDwveG1wOkNyZWF0ZURhdGU+CiAgICAgICAgIDx4bXA6TWV0YWRhdGFEYXRlPjIwMTktMDEtMjZUMTQ6MDI6NDEtMDU6MDA8L3htcDpNZXRhZGF0YURhdGU+CiAgICAgICAgIDx4bXA6TW9kaWZ5RGF0ZT4yMDE5LTAxLTI2VDE0OjAyOjQxLTA1OjAwPC94bXA6TW9kaWZ5RGF0ZT4KICAgICAgICAgPHhtcE1NOkluc3RhbmNlSUQ+eG1wLmlpZDo0ZjI2MzYxNy0wNDViLTEzNGMtOWJhMy0zZmM5NDlhNGE3NTA8L3htcE1NOkluc3RhbmNlSUQ+CiAgICAgICAgIDx4bXBNTTpEb2N1bWVudElEPmFkb2JlOmRvY2lkOnBob3Rvc2hvcDphNWRjOWQ4OS0yMTljLTExZTktYmVhOC1lNTA2MDlhNzA0NTg8L3htcE1NOkRvY3VtZW50SUQ+CiAgICAgICAgIDx4bXBNTTpPcmlnaW5hbERvY3VtZW50SUQ+eG1wLmRpZDphMzlhYjZkNC1mYzFlLTlhNDMtODU5Zi1hZDg3ZmU0ZDM1ZWQ8L3htcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD4KICAgICAgICAgPHhtcE1NOkhpc3Rvcnk+CiAgICAgICAgICAgIDxyZGY6U2VxPgogICAgICAgICAgICAgICA8cmRmOmxpIHJkZjpwYXJzZVR5cGU9IlJlc291cmNlIj4KICAgICAgICAgICAgICAgICAgPHN0RXZ0OmFjdGlvbj5jcmVhdGVkPC9zdEV2dDphY3Rpb24+CiAgICAgICAgICAgICAgICAgIDxzdEV2dDppbnN0YW5jZUlEPnhtcC5paWQ6YTM5YWI2ZDQtZmMxZS05YTQzLTg1OWYtYWQ4N2ZlNGQzNWVkPC9zdEV2dDppbnN0YW5jZUlEPgogICAgICAgICAgICAgICAgICA8c3RFdnQ6d2hlbj4yMDE5LTAxLTI2VDEzOjMxOjEyLTA1OjAwPC9zdEV2dDp3aGVuPgogICAgICAgICAgICAgICAgICA8c3RFdnQ6c29mdHdhcmVBZ2VudD5BZG9iZSBQaG90b3Nob3AgRWxlbWVudHMgMTQuMCAoV2luZG93cyk8L3N0RXZ0OnNvZnR3YXJlQWdlbnQ+CiAgICAgICAgICAgICAgIDwvcmRmOmxpPgogICAgICAgICAgICAgICA8cmRmOmxpIHJkZjpwYXJzZVR5cGU9IlJlc291cmNlIj4KICAgICAgICAgICAgICAgICAgPHN0RXZ0OmFjdGlvbj5zYXZlZDwvc3RFdnQ6YWN0aW9uPgogICAgICAgICAgICAgICAgICA8c3RFdnQ6aW5zdGFuY2VJRD54bXAuaWlkOjE0YjAzYzBhLWM4ZGYtMTg0Yi1hZmU0LTlhM2YxN2JhNzAxYjwvc3RFdnQ6aW5zdGFuY2VJRD4KICAgICAgICAgICAgICAgICAgPHN0RXZ0OndoZW4+MjAxOS0wMS0yNlQxMzozMToxMi0wNTowMDwvc3RFdnQ6d2hlbj4KICAgICAgICAgICAgICAgICAgPHN0RXZ0OnNvZnR3YXJlQWdlbnQ+QWRvYmUgUGhvdG9zaG9wIEVsZW1lbnRzIDE0LjAgKFdpbmRvd3MpPC9zdEV2dDpzb2Z0d2FyZUFnZW50PgogICAgICAgICAgICAgICAgICA8c3RFdnQ6Y2hhbmdlZD4vPC9zdEV2dDpjaGFuZ2VkPgogICAgICAgICAgICAgICA8L3JkZjpsaT4KICAgICAgICAgICAgICAgPHJkZjpsaSByZGY6cGFyc2VUeXBlPSJSZXNvdXJjZSI+CiAgICAgICAgICAgICAgICAgIDxzdEV2dDphY3Rpb24+c2F2ZWQ8L3N0RXZ0OmFjdGlvbj4KICAgICAgICAgICAgICAgICAgPHN0RXZ0Omluc3RhbmNlSUQ+eG1wLmlpZDo0ZjI2MzYxNy0wNDViLTEzNGMtOWJhMy0zZmM5NDlhNGE3NTA8L3N0RXZ0Omluc3RhbmNlSUQ+CiAgICAgICAgICAgICAgICAgIDxzdEV2dDp3aGVuPjIwMTktMDEtMjZUMTQ6MDI6NDEtMDU6MDA8L3N0RXZ0OndoZW4+CiAgICAgICAgICAgICAgICAgIDxzdEV2dDpzb2Z0d2FyZUFnZW50PkFkb2JlIFBob3Rvc2hvcCBFbGVtZW50cyAxNC4wIChXaW5kb3dzKTwvc3RFdnQ6c29mdHdhcmVBZ2VudD4KICAgICAgICAgICAgICAgICAgPHN0RXZ0OmNoYW5nZWQ+Lzwvc3RFdnQ6Y2hhbmdlZD4KICAgICAgICAgICAgICAgPC9yZGY6bGk+CiAgICAgICAgICAgIDwvcmRmOlNlcT4KICAgICAgICAgPC94bXBNTTpIaXN0b3J5PgogICAgICAgICA8ZGM6Zm9ybWF0PmltYWdlL3BuZzwvZGM6Zm9ybWF0PgogICAgICAgICA8cGhvdG9zaG9wOkNvbG9yTW9kZT4zPC9waG90b3Nob3A6Q29sb3JNb2RlPgogICAgICAgICA8cGhvdG9zaG9wOklDQ1Byb2ZpbGU+c1JHQiBJRUM2MTk2Ni0yLjE8L3Bob3Rvc2hvcDpJQ0NQcm9maWxlPgogICAgICAgICA8dGlmZjpPcmllbnRhdGlvbj4xPC90aWZmOk9yaWVudGF0aW9uPgogICAgICAgICA8dGlmZjpYUmVzb2x1dGlvbj45NjAwMDAvMTAwMDA8L3RpZmY6WFJlc29sdXRpb24+CiAgICAgICAgIDx0aWZmOllSZXNvbHV0aW9uPjk2MDAwMC8xMDAwMDwvdGlmZjpZUmVzb2x1dGlvbj4KICAgICAgICAgPHRpZmY6UmVzb2x1dGlvblVuaXQ+MjwvdGlmZjpSZXNvbHV0aW9uVW5pdD4KICAgICAgICAgPGV4aWY6Q29sb3JTcGFjZT4xPC9leGlmOkNvbG9yU3BhY2U+CiAgICAgICAgIDxleGlmOlBpeGVsWERpbWVuc2lvbj4zMjwvZXhpZjpQaXhlbFhEaW1lbnNpb24+CiAgICAgICAgIDxleGlmOlBpeGVsWURpbWVuc2lvbj4zMjwvZXhpZjpQaXhlbFlEaW1lbnNpb24+CiAgICAgIDwvcmRmOkRlc2NyaXB0aW9uPgogICA8L3JkZjpSREY+CjwveDp4bXBtZXRhPgogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIAogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAKICAgICAgICAgICAgICAgICAgICAgICAgICAgIAo8P3hwYWNrZXQgZW5kPSJ3Ij8+fDpo+wAAACBjSFJNAAB6JQAAgIMAAPn/AACA6QAAdTAAAOpgAAA6mAAAF2+SX8VGAAAAaElEQVR42mL8//8/w0ACJoYBBqMOGHXAqANGHcBCrMJORjGSiszy/68YqeoAqKHEOpb6IQAD72RV8coLPb49mghHHTDqgGFaEpKbz6nqAFJKOGIB42ireNQBow4YdcCIdwAAAAD//wMAW8MSs2NvdMsAAAAASUVORK5CYII="
];
function show_station_information(identity)
{
	console.log(identity);
	$.getJSON("<?php echo $serveradress ?>", function(data){
		$("#Showstation_info").modal()
		//console.log(data.nodes[identity]);
		var name = identity;
		var ip_adress = data.nodes[identity].addr;
		var location = data.nodes[identity].NodeLocation;
		if(location == null)
		{
			location = data.nodes[identity].nodeLocation;
		}
		var tg_tg = data.nodes[identity].tg;
		var swVer_tg = data.nodes[identity].swVer;
	
		$('#stname').html(name.toString());
		$('#tg_tg').html(tg_tg.toString());
		$('#swVer_tg').html(swVer_tg.toString());
		
	

		
		$('#nodeLocation_stn').html(location.toString());
		$('#stn_tg').html('');
		for(var k in data.nodes[identity].monitoredTGs)
		{
			$('#stn_tg').append(''+data.nodes[identity].monitoredTGs[k]+' ');
		}
		$('#optable_stn').html('');
		for(var qth in data.nodes[identity].qth)
		{
			var sysop_tg = data.nodes[identity].qth[qth].sysop;
			if(sysop_tg == null)
			{
				sysop_tg = data.nodes[identity].sysop;
				if(sysop_tg == null)
				{
					sysop_tg=" &nbsp;";
				}
			}
			$('#sysop_tg').html(sysop_tg.toString());
			
			for(var rx in data.nodes[identity].qth[qth].rx)
			{
				var name = data.nodes[identity].qth[qth].rx[rx].name;
				var sqlType = data.nodes[identity].qth[qth].rx[rx].sqlType;
				var freq = data.nodes[identity].qth[qth].rx[rx].freq;

				var qth_name =data.nodes[identity].qth[qth].name
				if(freq != null)
					freq = freq.toFixed(4);
				var enabled = data.nodes[identity].qth[qth].rx[rx].enabled
				if(enabled == undefined)
				{
					enabled= "true";
				}
				$('#optable_stn').append('<tr><td>'+name+'</td><td>'+qth_name+'</td><td>'+sqlType+'</td><td>'+freq+'</td><td>'+enabled+'</td></tr>');

			}
		}
				

		
	});

	 
}
var disable_text_map  =0;
function set_disable_text(val)
{

	disable_text_map = val;
	map.updateSize();
}

function update_tx_station(lat,lon,idn,tg,label,active,talker)
{

	var update_icon = vectorSource.getFeatureById("stn_"+idn);

	if(update_icon!= null )
	{

    	if(tg_collors[tg] == null)
    	{
    		tg_collors[tg]= new Array();
    		tg_collors[tg]["id"] =tg;
    		tg_collors[tg]["color"] =random_css_collor();
    		tg_collors[tg]["TXT"] ="";
    	}
    	var geometry =update_icon.getGeometry()
    	var style = update_icon.getStyle();
    	var html = "stn_"+idn;
//    	console.log(style);
    	var collor = tg_collors[tg]['color'];
    
    
    	

    	html =html.replace(/ /g,"_");
    	var canvas = document.createElement('canvas');
    	var ctx = canvas.getContext('2d');
    	canvas.setAttribute('width', 60);
    	canvas.setAttribute('height', 100);
    	canvas.setAttribute('id', 'icon_'+html);
    
    
    	ctx.beginPath();
    
    	
    	
    	ctx.arc(30, 30, 25, 25, Math.PI * 2, true); // Outer circle
    
    
    	
    	ctx.moveTo(5, 35);
    	ctx.lineTo(30, 80);
    	ctx.lineTo(55, 35);
    	ctx.lineWidth = 3;
    	ctx.stroke();
    	ctx.fillStyle =collor;
    	ctx.fill();
    	ctx.beginPath();
    	ctx.arc(30, 30, 10, 0, Math.PI * 2, true); // Outer circle
    	if(talker == true)
    	{
    		ctx.fillStyle ="#000080";
    	}
    	else if(active == false)
    		ctx.fillStyle ="#FFFFFF";
    	else
    	{
    		ctx.fillStyle ="#FF4500";
    		ctx.lineWidth = 1;
    		ctx.stroke();
    	}
    	
    	ctx.fill();
    	ctx.beginPath();
    	ctx.scale(-0.5, -0.5);
    
    

    	icon_save[idn] = canvas.toDataURL();
    	
    
    	iconFeature = new ol.Feature({
    	    geometry: new ol.geom.Point(new ol.proj.transform([lon,lat], 'EPSG:4326', 'EPSG:3857')),
    	});
    	var lablel_text="";
    	var newZoom = map.getView().getZoom();


    	if(newZoom >= 7 && disable_text_map == 0)
    	{
    		lablel_text = label;
    	}
    	
    
    	var iconStyle = new ol.style.Style({
    	        image: new ol.style.Icon(({
    	        anchor: [0.5, 1.0],
    	        anchorXUnits: 'fraction',
    	        anchorYUnits: 'fraction',
    	        opacity: 0.9,
    	        src: icon_save[idn],
    			scale:0.5
    	        })),
    	        text: new ol.style.Text({
    	                     font: '12px helvetica,sans-serif',
    	                     text: lablel_text,
    	                     fontSize: 12,
    						 scale:1.2,
    	                     fill: new ol.style.Fill({
    	                         color: '#000'
    	                     }),
    	                     stroke: new ol.style.Stroke({
    	                         color: '#fff',
    	                         width: 2
    	                     })
    	                  })
    	});
    
    	update_icon.setStyle(iconStyle);
	}
	
}


function connect_reflector()
{
	kill_loop=1;
	vectorSource.clear();
	Barsource.clear();
	map.overlays_.clear();
	map_lon_lat = new Array()
	map_pos_i=0;



	setTimeout(function(){
		add_tx_station();
		// add recivers on start
		//prosess_json_reflecktor();



		setTimeout(function(){
			
			kill_loop=0;
		//	update_tx_station_loop()

		}, 500); 
		
	}, 200); 


	
	
	

	
	
}

function addmarker(iconindex,lat, lon,label,html)
{
        iconFeature = new ol.Feature({
            geometry: new ol.geom.Point(new ol.proj.transform([lon,lat], 'EPSG:4326', 'EPSG:3857')),
        });
    	var lablel_text="";
    	var newZoom = map.getView().getZoom();



        var iconStyle = new ol.style.Style({
                image: new ol.style.Icon(({
                anchor: [0.5, 1.0],
                anchorXUnits: 'fraction',
                anchorYUnits: 'fraction',
                opacity: 0.9,
                src: ico[iconindex],
				scale:1
                })),
                text: new ol.style.Text({
                             font: '12px helvetica,sans-serif',
                             text: label,
                             fontSize: 12,
							 scale:1,
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
        iconFeature.setId(html);
		vectorSource.addFeature(iconFeature);
        
        return vectorLayer;
}

		
function addtext(iconindex,lat, lon,id)
{
        iconFeature = new ol.Feature({
            geometry: new ol.geom.Point(new ol.proj.transform([lon,lat], 'EPSG:4326', 'EPSG:3857')),
        });

        var iconStyle = new ol.style.Style({
				
                text: new ol.style.Text({
                             font: '20px helvetica,sans-serif',
                             text: "0",
							 offsetX:0,
							 offsetY:-80,
                             fontSize: 60,
							 scale:1,
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
        iconFeature.setId(id);
		Barsource.addFeature(iconFeature);

        return vectorLayer;
}
function removeFeatures() 
{

	var features = vectorSource.getFeatures();
	for(var i=0; i< features.length && i<10; i++) {
		vectorSource.removeFeature(features[i]);
	}
	var features = Barsource.getFeatures();
	for(var i=0; i< features.length && i<10; i++) {
		Barsource.removeFeature(features[i]);
	}	
	
	
	

}




function start_data()
{

//   var jsonStream = new EventSource("http://sk2riugw.dyndns.org:1535/json");
//    jsonStream.onmessage = function (e) {
//        var message = JSON.parse(e.data);
//		receiver = message.rx["RX70"]
//		console.log(receiver);
	
    
//    };


}
var follow_signal =0;
function toogle_AutoFollow()
{
	if(follow_signal == 0)
	{
		follow_signal =1;
		$("#Autofollow_text").css('color', 'blue');
	}
	else
	{
		follow_signal =0;
		$("#Autofollow_text").css('color', '');
	}
}
var cordinates;
function update_text_byid(id,text,sql)
{
	var update_text = Barsource.getFeatureById(id)
	var geometry =update_text.getGeometry();
	
	cordinates  = geometry.getCoordinates();
	
	
	style =update_text.getStyle();
	oo =style.getText();
//	console.log(update_text);
	oo.setText(text);
//	console.log(oo.getText());
	var aa =oo.getFill();
	if(sql == 'active')
	{
		 
		aa.setColor("#1932F7");
		// Autofollow Funtion
		if(follow_signal ==1)
		{
			setmap_noTransform(cordinates[0],cordinates[1],13);
		}

		
	}
	else if(sql == 'open')
	{
		aa.setColor("#E31013");

	}

	else
	{
		aa.setColor("#000");
	}
	
	
	Barsource.refresh()
	
	
}
function add_repeater_node(lat, lon,label,idn)
{

	



	addmarker(0,lat, lon,label,"aa"+idn);
	addtext(0,lat, lon,idn);
	Draw_bar(idn, lat,lon);

}
function add_repeater_transmiter(lat, lon,label,idn,tg)
{
//	console.log(tg_collors[tg]['color']);
	canvas_icon("stn_"+idn,lat, lon,label,tg_collors[tg]['color']);
	//addtext(0,lat, lon,idn);

}
var instervalls;

function hueToRgb (p, q, t) 
{
  if (t < 0) t += 1
  if (t > 1) t -= 1
  if (t < 1/6) return p + (q - p) * 6 * t
  if (t < 1/2) return q
  if (t < 2/3) return p + (q - p) * (2/3 - t) * 6

  return p
}


function hslToRgb (h, s, l) {
	  // Achromatic
	  if (s === 0) return [l, l, l]
	  h /= 360

	  var q = l < 0.5 ? l * (1 + s) : l + s - l * s
	  var p = 2 * l - q

	  return [
	    Math.round(hueToRgb(p, q, h + 1/3) * 255),
	    Math.round(hueToRgb(p, q, h) * 255),
	    Math.round(hueToRgb(p, q, h - 1/3) * 255)
	  ]
	}




function random_css_collor()
{
	 var data = hslToRgb (360 * Math.random(), (25 + 70 * Math.random()), (85 + 10 * Math.random()))
	  return "RGB(" + data[0] + ',' +data[1]
       + ',' + data[2]
      + ')'
}
function componentToHex(c) {
	  var hex = c.toString(16);
	  return hex.length == 1 ? "0" + hex : hex;
	}
	
//random hex string generator
var randHex = function(len) {
  var maxlen = 8,
      min = Math.pow(16,Math.min(len,maxlen)-1) 
      max = Math.pow(16,Math.min(len,maxlen)) - 1,
      n   = Math.floor( Math.random() * (max-min+1) ) + min,
      r   = n.toString(16);
  while ( r.length < len ) {
     r = r + randHex( len - maxlen );
  }
  return r;
};

function Hex_random_css_collor() 
{

	  return "#" + randHex(6);
}





var ov = new Array();

function addimage(src,lamin,lomin,lamax,lomax)
{
    var c1 = new ol.proj.transform([lomin , lamin], 'EPSG:4326', 'EPSG:3857');
    var c2 = new ol.proj.transform([lomax , lamax], 'EPSG:4326', 'EPSG:3857');
    var cx = new ol.proj.transform([(lomin + lomax) / 2, (lamin + lamax) / 2], 'EPSG:4326', 'EPSG:3857');
    var dx = (c2[0] - c1[0]) / 2;
    var dy = (c2[1] - c1[1]) / 2;

    c1[0] = cx[0] - dx;
    c2[0] = cx[0] + dx;
    c1[1] = cx[1] - dy;
    c2[1] = cx[1] + dy;

    ov[src] = new ol.layer.Image({
            source: new ol.source.ImageStatic({
            url: "covrige/"+src,
            imageExtent: [c1[0], c1[1], c2[0], c2[1]]
        })
    });
    ov[src].setZIndex(parseInt(0, 10) || 0);  
     
    this.coverigeGroup.getLayers().array_.push(ov[src])
    //map.addLayer(ov[src]);
    return ov[src];
}

var  map_lon_lat = new Array();
var map_pos_i=0;


function prosess_json_reflecktor()
{

  $.getJSON("<?php echo $serveradress ?>", function(data){
  //console.log(data);
    
		for(var k in data.nodes){
			
		    if(data.nodes[k].hidden == true && secret_santa == 0)
		    {
		    	delete data.nodes[k];
		    	
		    }
		    if ( k.includes("/")) {
				var new_call = k.replace("/",'-');
		    	data.nodes[new_call] = data.nodes[k]; 
		        delete data.nodes[k];
		    }

		    
		}
		
		for(var k in data.nodes){
		    for(var qth in data.nodes[k].qth)
			    {

				var count_rx =0;
		        for(var qth1 in data.nodes[k].qth[qth].rx)
			    {
		        	count_rx++;
			    }
			    var tx_count =0;
		        for(var qth1 in data.nodes[k].qth[qth].tx)
			    {
		        	tx_count++;
			    }

			    

		    	
		        for(var qth1 in data.nodes[k].qth[qth].rx)
			    {
			        
    				var lat =parseFloat(data.nodes[k].qth[qth].pos.lat);
    				var lon =parseFloat(data.nodes[k].qth[qth].pos.long);

   
    				
    				var name =  k+" "+data.nodes[k].qth[qth].name;
    				console.log(data.nodes[k].qth[qth].rx[qth1].name);
    				
    				var talkgroup =data.nodes[k].tg;
    				var idn=k+qth1;
    				console.log(idn);
    				console.log(data.nodes[k].qth[qth].rx[qth1].name);
    				idn =idn.replace(/ /g,"_");

    	
//   				console.log(tx_count);
    				if(tx_count>0)
    				{
        				if(count_rx >1)
        				{
            				
            				var group_idn= k+data.nodes[k].qth[qth].name;
            				group_idn =group_idn.replace(/ /g,"_");
            				if(!valudate_if_exist(lon,lat))
            				{
            					map_lon_lat[map_pos_i] = new Array();
            					map_lon_lat[map_pos_i]['lat'] = lat;
            					map_lon_lat[map_pos_i]['lon'] = lon;
            					map_pos_i++;
            					
            				add_repeater_node(lat, lon,name,group_idn);
            				
            				}
							if(Barsource.getFeatureById(group_idn) == null)
							{
 //               				console.log("tx: " +group_idn+" "+lat+" "+lon);
            					addtext(0,lat, lon,group_idn);
            					Draw_bar(group_idn, lat,lon);
							}
							
        					
        				}
        				else
        				{
//        					console.log("else tx: " +idn+" "+lat+" "+lon);

							console.log("kallekulla");
						    console.log(idn);
        					addtext(0,lat, lon,idn);
        					Draw_bar(idn, lat,lon);
        				}
    				}
    				else
    				{
        				if(count_rx >1)
        				{

        					var group_idn= k+data.nodes[k].qth[qth].name;
        					group_idn =group_idn.replace(/ /g,"_");
							if(Barsource.getFeatureById(group_idn) == null)
							{
								station_identifire[group_idn] =k;

								if(!valudate_if_exist(lon,lat))
	            				{
	            					map_lon_lat[map_pos_i] = new Array();
	            					map_lon_lat[map_pos_i]['lat'] = lat;
	            					map_lon_lat[map_pos_i]['lon'] = lon;
	            					map_pos_i++;

	            					
        					    	add_repeater_node(lat, lon,name,group_idn);
	            				}
							}
        					
        				}
        				else
        				{

            				if(!valudate_if_exist(lon,lat))
            				{
            					console.log("else rx: " +idn+" "+lat+" "+lon);
            					map_lon_lat[map_pos_i] = new Array();
            					map_lon_lat[map_pos_i]['lat'] = lat;
            					map_lon_lat[map_pos_i]['lon'] = lon;
            					map_pos_i++;
            					
     							idn =idn.replace(/ /g,"_");
     							station_identifire[idn] =k;
     							console.log(idn);
     							add_repeater_node(lat, lon,name,idn);
            				}
 				
        					//
        				}
    					

    					
    				}
    
    				
    				//setmap(lat, lon,5);

		        }

			}	
		}
			
	
	
			

		
		
    
  });

}
function valudate_if_exist(lon,lat)
{

	for(var i in map_lon_lat)
	{
		if(map_lon_lat[i]['lat']  ==lat && map_lon_lat[i]['lon'] ==  lon )
			return true;
	}	
	

	
	return false;
}



function get_year_static()
{
	var date_value = $('#Datepicker_graph').val();
	var select1= $('#filterpicker_repeater_year').val();
	var select2= $('#filterpicker_talgroup_year').val();



	
	$.get( "get_statistics.php", { date: date_value, mouth:"true", filterpicker_repeater_year: select1, filterpicker_talgroup_year: select2} )
	  .done(function( data ) {
			console.log(data);


		  var json_data_year = JSON.parse(data);

		  var data_aray_year = new Array();
		  for(var mouth in json_data_year)
		  {
			  data_aray_year[mouth-1]= json_data_year[mouth]['unixtime'];

		  }
		  $("#toplist_table tbody").html("");
		  for(var top in json_data_year.Toplist)
		  {
			  $("#toplist_table tbody").append("<tr><td>"+json_data_year.Toplist[top]['day']+"</td><td>"+json_data_year.Toplist[top]['Secound']+"</td></tr>");

		  }

		 
		  		  

        		  
        	$('#canvas_grap_holder3').html("");
        	$('#canvas_grap_holder3').html('<canvas id="Graph3"  width="700px" height="500px" class="img-responsive" ></canvas>');
        	
        	var canvas = document.getElementById('Graph3')
        	canvas.width = canvas.width; 
        	
        	var ctx = document.getElementById('Graph3').getContext('2d');
        	var barChartData = {
        			labels: ['<?php echo _('January')?>', '<?php echo _('February')?>', '<?php echo _('March')?>', '<?php echo _('April')?>', '<?php echo _('May')?>', '<?php echo _('June')?>', '<?php echo _('July')?>','<?php echo _('August')?>','<?php echo _('September')?>','<?php echo _('October')?>','<?php echo _('November')?>','<?php echo _('December')?>'],
        			datasets: [{
        				label: '<?php echo _('Total')?>',
        				backgroundColor: [
        					"#6495ED",
        					"#6495ED",
        					"#7CFC00",
        					"#7CFC00",
        					"#bef211",
        					"#F08080",
        					"#F08080",
        					"#FFA500",
        					"#DAA520",
        					"#808080",
        					"#808080",
        					"#234d80"
        					
        					
        				],
        				yAxisID: 'y-axis-1',
        				data:data_aray_year
        			}]};
        	
        	window.myBar2 = new Chart(ctx, {
        		type: 'bar',
        		data: barChartData,
        		options: {
        			responsive: true,
        			maintainAspectRatio:false,
        			title: {
        				display: true,
        				text: '<?php echo addslashes(_('Year statistics'))?>'
        			},
        			tooltips: {
        				mode: 'index',
        				intersect: true
        			},
        			scales: {
        				yAxes: [{
        					type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
        					display: true,
        					position: 'left',
        					id: 'y-axis-1',
        	                ticks: {
        	                    // Include a dollar sign in the ticks
        	                    beginAtZero: true,
        	                    callback: function(value, index, values) {
        	                        return secondsToDHMS( value);
        	                    }
        	                }
        				}],
        			},
        			tooltips: {
        	            // Disable the on-canvas tooltip
        	            enabled: true,
        	            callbacks: {
        	                label: function(tooltipItem, data) {
        	                    var label = data.datasets[tooltipItem.datasetIndex].label;
        	                    var talktime = data.datasets[0].data[tooltipItem.index];
 
        						
        	                    if (label) {
        	                        label += ': ';
        	                    }
        	                    label += secondsToDHMS(talktime);
        	                    return label;
        	                }
        	            }

        	        }
        	        
        			
        		}
        	});

	

	  });

	<?php if (detect_empty_cache_table () == true ){?>



	 const myTimeout = setTimeout(get_station_chat_year, 450);


	<?php }?>


}


function get_statistics()
{

	$('#canvas_grap_holder').html("");
	$('#canvas_grap_holder').html('<canvas id="Graph" width="400px" height="400px"></canvas>');
	var canvas = document.getElementById('Graph')
	canvas.width = canvas.width; 
	var ctx = document.getElementById('Graph').getContext('2d');
	var barDatafromJSON;
	var date_value = $('#Datepicker_graph').val();


    var barDatafromJSON= {
    	labels: [''],
    	datasets: [
    	]
    
    };


window.myBara = new Chart(ctx, {
	type: 'bar',
	data: barDatafromJSON,
	options: {
		responsive: true,
		maintainAspectRatio: false,
		legend: {
			position: 'top',
		},
		title: {
			display: true,
			text: '<?php echo _("Talkgroup activity")?> '+date_value
		},
		scales: {
            yAxes: [{
                ticks: {
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                        return secondsToDHMS( value);
                    }
            	,beginAtZero: true
                }
            }]
        },
		tooltips: {
            // Disable the on-canvas tooltip
            enabled: true,
            callbacks: {
                label: function(tooltipItem, data) {
                    var label = data.datasets[tooltipItem.datasetIndex].label;
                    var talktime = data.datasets[tooltipItem.datasetIndex].data[0];
      
					
                    if (label) {
                        label += ': ';
                    }
                    label += secondsToDHMS(talktime);
                    return label;
                }
            }

        }
	}
});

	
	$.get( "get_statistics.php", { date: date_value} )
	  .done(function( data ) {
		  var jsondata = JSON.parse(data); 





	    	
		  var i =0;
		  for(var talkgroup in jsondata)
		  {
		    	if(tg_collors[talkgroup] == null)
		    	{
		    		tg_collors[talkgroup]= new Array();
		    		tg_collors[talkgroup]["id"] =talkgroup;
		    		tg_collors[talkgroup]["color"] =random_css_collor();
		    		tg_collors[talkgroup]["TXT"] ="";
		    	}
		    	

			  if(jsondata[talkgroup].unixtime >0)
			  {
  

    			  i++;

   
    				var newDataset = {
    					label: talkgroup,
    					backgroundColor: tg_collors[talkgroup]['color'].trim(),
    					borderColor:  tg_collors[talkgroup]['color'].trim(),
    					borderWidth: 1,
    					data: []
    				};

    				
						var datato_push =jsondata[talkgroup].unixtime;
    					newDataset.data.push(datato_push);
    				

    					barDatafromJSON.datasets.push(newDataset);

    				
    			  
			  }
			  
		  }
		  

		  window.myBara.update();

	



	  });


	 setTimeout(get_station_chat, 20);
	  

}
var show_all_tg =1;
var use_hour=0;
var station_filer_time ="";

function set_repater_statics_time()
{
	station_filer_time = $('#filterpicker_repeater_time :selected').val();
	console.log(station_filer_time);
	get_statistics_hour();
}


function get_statistics_hour()
{

	$('#canvas_grap_holder1').html("");
	$('#canvas_grap_holder1').html('<canvas id="Graph1" width="400px" height="400px"></canvas>');
	var canvas = document.getElementById('Graph1')
	canvas.width = canvas.width; 
	var ctx = document.getElementById('Graph1').getContext('2d');
	var barDatafromJSON;
	var date_value = $('#Datepicker_graph').val();

    var barDatafromJSON= {
    	labels: [''],
    	datasets: [
    	]
    
    };



	
	$.get( "get_statistics.php", { date: date_value, time:"true",station:station_filer_time} )
	  .done(function( data ) {
		  var jsondata = JSON.parse(data); 
		  //console.log(jsondata);
		 


	    	
		  var i =0;
		  // fuling fÃ¶r time 0-24
		  var data_to_set = new Array();
		  var labels = new Array();
		  console.log(jsondata);


			  
		  for (talkgroup = 0; talkgroup < 24;talkgroup++) 
		  {
				if(jsondata[talkgroup].unixtime == null || jsondata[talkgroup].unixtime  <0)
					jsondata[talkgroup].unixtime =0;
		
			  data_to_set[talkgroup] ={x:talkgroup, y:parseInt(jsondata[talkgroup].unixtime)};
			  labels[talkgroup] = talkgroup+":00"

  
			  
		  }

		    var barDatafromJSON= {
		        	labels: labels,
		        	datasets: [
		        		{
		    			label: '<?php echo _("Reflektor time in S")?>',
						backgroundColor: "#6495ED",
						borderColor: "#6495ED",
						fill: false,
						data: data_to_set
		        		}
		    ]
		    };
	


		    		  

		  window.myLine = new Chart(ctx, {
				type: 'line',
				data: barDatafromJSON,
				options: {
					responsive: true,
					maintainAspectRatio: false,
					legend: {
						position: 'top',
					},
					title: {
						display: true,
						text: '<?php echo _("Reflektor time in S")?> '+date_value
					},
					scales: {
			            yAxes: [{
			                ticks: {
			                    // Include a dollar sign in the ticks
			                    callback: function(value, index, values) {
			                        return secondsToDHMS( value);
			                    }
			                }
			            }]
			        },
					tooltips: {
			            // Disable the on-canvas tooltip
			            enabled: true,
			            
		            callbacks: {
		                label: function(tooltipItem, data) {
		                    var label = data.datasets[tooltipItem.datasetIndex].label;
		                    var talktime = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].y;
		          
				
							
		                    if (label) {
		                        label += ': ';
		                    }
		                    label += secondsToDHMS(talktime);
		                    return label;
		                }
		            }
				   }
				}
				
					
				
			});

			if(show_all_tg ==1 )
			{
				var talkgropoup_array = new Array();

				  for (time = 0; time < 24;time++) 
				  {
					  
					  for(var talkgroup in jsondata[time].TG)
					  {
						  if(talkgropoup_array[talkgroup] == 'undefined' || !(talkgropoup_array[talkgroup] instanceof Array) )
						  {
						 	 talkgropoup_array[talkgroup]= new Array();
						  }
						  
						  if(jsondata[time].TG[talkgroup] <= 0)
						  {
							  talkgropoup_array[talkgroup][time] ={x: time, y:0};
						  }
						  else
						  {
							  talkgropoup_array[talkgroup][time] = {x:time  ,y: jsondata[time].TG[talkgroup]};
						  }
					  }
						  

				  }

				  for(var talkgroup in talkgropoup_array)
				  {

				    	if(tg_collors[talkgroup] == null)
				    	{
				    		tg_collors[talkgroup]= new Array();
				    		tg_collors[talkgroup]["id"] =talkgroup;
				    		tg_collors[talkgroup]["color"] =random_css_collor();
				    		tg_collors[talkgroup]["TXT"] ="";
				    	}

				    	

						var newDataset = {
		    					label: talkgroup+ ' <?php echo _('time')?>',
		    					backgroundColor: tg_collors[talkgroup]['color'].trim(),
		    					borderColor:  tg_collors[talkgroup]['color'].trim(),
		    					fill: false,
		    					data: talkgropoup_array[talkgroup]
		    				};
		
		    		

		    				
				
		
		    					barDatafromJSON.datasets.push(newDataset);


								  
					 

				  }
				  window.myLine.update();

				  
				  //console.log(talkgropoup_array[240]);

				
				
			}

		  
		  

	  });
}
var set_mounth_station ="";

function set_repater_statics_mounth()
{

	set_mounth_station = $('#filterpicker_repeater_mouth :selected').val();
	get_statistics_month();


}



function get_statistics_mounth()
{

	$('#Graph_mo_grap_holder').html("");
	$('#Graph_mo_grap_holder').html('<canvas id="Graph_mo" width="400px" height="400px"></canvas>');
	var canvas = document.getElementById('Graph_mo')
	canvas.width = canvas.width; 
	var ctx = document.getElementById('Graph_mo').getContext('2d');
	var barDatafromJSON;
	var date_value = $('#Datepicker_graph').val();


    var barDatafromJSON= {
    	labels: [''],
    	datasets: [
    	]
    
    };


window.myBara = new Chart(ctx, {
	type: 'bar',
	data: barDatafromJSON,
	options: {
		responsive: true,
		maintainAspectRatio: false,
		legend: {
			 display: true ,//This will do the task
			position: 'right',
		},
		title: {
			display: true,
			text: '<?php echo _("Talkgroup activity")?> '
		},
		scales: {
            yAxes: [{
                ticks: {
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                        return secondsToDHMS( value);
                    }
            	,beginAtZero: true
                }
            }]
        },
		tooltips: {
            // Disable the on-canvas tooltip
            enabled: true,
            callbacks: {
                label: function(tooltipItem, data) {
                    var label = data.datasets[tooltipItem.datasetIndex].label;
                    var talktime = data.datasets[tooltipItem.datasetIndex].data[0];
      
					
                    if (label) {
                        label += ': ';
                    }
                    label += secondsToDHMS(talktime);
                    return label;
                }
            }

        }
	}
});

	
	$.get( "get_statistics.php", { date: date_value , cahce_mouth_tg : '1'} )
	  .done(function( data ) {
		  var jsondata = JSON.parse(data); 





	    	
		  var i =0;
		  for(var talkgroup in jsondata)
		  {
		    	if(tg_collors[talkgroup] == null)
		    	{
		    		tg_collors[talkgroup]= new Array();
		    		tg_collors[talkgroup]["id"] =talkgroup;
		    		tg_collors[talkgroup]["color"] =random_css_collor();
		    		tg_collors[talkgroup]["TXT"] ="";
		    	}
		    	

			  if(jsondata[talkgroup].unixtime >0)
			  {
  

    			  i++;

   
    				var newDataset = {
    					label: talkgroup,
    					backgroundColor: tg_collors[talkgroup]['color'].trim(),
    					borderColor:  tg_collors[talkgroup]['color'].trim(),
    					borderWidth: 1,
    					data: []
    				};

    				
						var datato_push =jsondata[talkgroup].unixtime;
    					newDataset.data.push(datato_push);
    				

    					barDatafromJSON.datasets.push(newDataset);

    				
    			  
			  }
			  
		  }
		  

		  window.myBara.update();

	



	  });

	get_station_chat_mo();
}

function get_statistics_year_tg()
{

	$('#Graph_ytg_grap_holder').html("");
	$('#Graph_ytg_grap_holder').html('<canvas id="Graph_tg_grap_ca" width="400px" height="400px"></canvas>');
	var canvas = document.getElementById('Graph_tg_grap_ca')
	canvas.width = canvas.width; 
	var ctx = document.getElementById('Graph_tg_grap_ca').getContext('2d');
	var barDatafromJSON;
	var date_value = $('#Datepicker_graph').val();


    var barDatafromJSON= {
    	labels: [''],
    	datasets: [
    	]
    
    };


window.myBara = new Chart(ctx, {
	type: 'bar',
	data: barDatafromJSON,
	options: {
		responsive: true,
		maintainAspectRatio: false,
		legend: {
			 display: true ,//This will do the task
			position: 'right',
		},
		title: {
			display: true,
			text: '<?php echo _("Talkgroup activity")?> '
		},
		scales: {
            yAxes: [{
                ticks: {
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                        return secondsToDHMS( value);
                    }
            	,beginAtZero: true
                }
            }]
        },
		tooltips: {
            // Disable the on-canvas tooltip
            enabled: true,
            callbacks: {
                label: function(tooltipItem, data) {
                    var label = data.datasets[tooltipItem.datasetIndex].label;
                    var talktime = data.datasets[tooltipItem.datasetIndex].data[0];
      
					
                    if (label) {
                        label += ': ';
                    }
                    label += secondsToDHMS(talktime);
                    return label;
                }
            }

        }
	}
});

	
	$.get( "get_statistics.php", { date: date_value , cahce_year_tg : '1'} )
	  .done(function( data ) {
		  var jsondata = JSON.parse(data); 





	    	
		  var i =0;
		  for(var talkgroup in jsondata)
		  {
		    	if(tg_collors[talkgroup] == null)
		    	{
		    		tg_collors[talkgroup]= new Array();
		    		tg_collors[talkgroup]["id"] =talkgroup;
		    		tg_collors[talkgroup]["color"] =random_css_collor();
		    		tg_collors[talkgroup]["TXT"] ="";
		    	}
		    	

			  if(jsondata[talkgroup].unixtime >0)
			  {
  

    			  i++;

   
    				var newDataset = {
    					label: talkgroup,
    					backgroundColor: tg_collors[talkgroup]['color'].trim(),
    					borderColor:  tg_collors[talkgroup]['color'].trim(),
    					borderWidth: 1,
    					data: []
    				};

    				
						var datato_push =jsondata[talkgroup].unixtime;
    					newDataset.data.push(datato_push);
    				

    					barDatafromJSON.datasets.push(newDataset);

    				
    			  
			  }
			  
		  }
		  

		  window.myBara.update();

	



	  });


}





function get_statistics_month()
{

	$('#canvas_grap_holder2').html("");
	$('#canvas_grap_holder2').html('<canvas id="Graph_month" width="400px" height="400px"></canvas>');
	var canvas = document.getElementById('Graph_month')
	canvas.width = canvas.width; 
	var ctx = document.getElementById('Graph_month').getContext('2d');
	var barDatafromJSON;
	var date_value = $('#Datepicker_graph').val();

    var barDatafromJSON= {
    	labels: [''],
    	datasets: [
    	]
    
    };



    date_value  = date_value.slice(0, -3);
	$.get( "get_statistics.php", { date_m: date_value, totalmount:"true",station: set_mounth_station} )
	  .done(function( data ) {
		  var jsondata = JSON.parse(data); 
		  console.log(jsondata);
		 


	    	
		  var i =0;
		  // fuling fÃ¶r time 0-24
		  var data_to_set = new Array();
		  var labels = new Array();
		  var collor = new Array();

		  for(var talkgroup in jsondata)
		  {

		      data_to_set[talkgroup] ={x:talkgroup, y:parseInt(jsondata[talkgroup].unixtime)};
			  labels[talkgroup] =jsondata[talkgroup].day;
		
  
			  
		  }


		  

		    var barDatafromJSON= {
		        	labels: labels,
		        	datasets: [
		        		{
		    			label: '<?php echo _("Month activity")?>',
						backgroundColor: "#6495ED",
						borderColor: "#6495ED",
						fill: false,
						data: data_to_set
		        		}
		    ]
		    };
	


		    		  

		  window.myLine = new Chart(ctx, {
				type: 'line',
				data: barDatafromJSON,
				options: {
					responsive: true,
					maintainAspectRatio: false,
					legend: {
						position: 'top',
						 display: false //This will do the task
					},
					title: {
						display: true,
						text: '<?php echo _("Month activity ")?>'+$('#Datepicker_graph').val()
					},
					scales: {
			            yAxes: [{
			                ticks: {
			                    // Include a dollar sign in the ticks
			                    callback: function(value, index, values) {
			                        return secondsToDHMS( value);
			                    }
			                }
			            }]
			        },
					tooltips: {
			            // Disable the on-canvas tooltip
			            enabled: true,
			            
		            callbacks: {
		                label: function(tooltipItem, data) {
		                    var label = data.datasets[tooltipItem.datasetIndex].label;
		                    var talktime = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].y;
		          
				
							
		                    if (label) {
		                        label += ': ';
		                    }
		                    label += secondsToDHMS(talktime);
		                    return label;
		                }
		            }
				   }
				}
				
					
				
			});
<?php 
/*
		  
			if(show_all_tg ==1 )
			{
				var talkgropoup_array = new Array();

				  for (time = 0; time < 24;time++) 
				  {
					  
					  for(var talkgroup in jsondata[time].TG)
					  {
						  if(talkgropoup_array[talkgroup] == 'undefined' || !(talkgropoup_array[talkgroup] instanceof Array) )
						  {
						 	 talkgropoup_array[talkgroup]= new Array();
						  }
						  
						  if(jsondata[time].TG[talkgroup] <= 0)
						  {
							  talkgropoup_array[talkgroup][time] ={x: time, y:0};
						  }
						  else
						  {
							  talkgropoup_array[talkgroup][time] = {x:time  ,y: jsondata[time].TG[talkgroup]};
						  }
					  }
						  

				  }

				  for(var talkgroup in talkgropoup_array)
				  {

				    	if(tg_collors[talkgroup] == null)
				    	{
				    		tg_collors[talkgroup]= new Array();
				    		tg_collors[talkgroup]["id"] =talkgroup;
				    		tg_collors[talkgroup]["color"] =random_css_collor();
				    		tg_collors[talkgroup]["TXT"] ="";
				    	}

				    	

						var newDataset = {
		    					label: talkgroup+ ' <?php echo _('time')?>',
		    					backgroundColor: tg_collors[talkgroup]['color'].trim(),
		    					borderColor:  tg_collors[talkgroup]['color'].trim(),
		    					fill: false,
		    					data: talkgropoup_array[talkgroup]
		    				};
		
		    		

		    				
				
		
		    					barDatafromJSON.datasets.push(newDataset);


								  
					 

				  }
				  window.myLine.update();

				  
				  //console.log(talkgropoup_array[240]);

				
				
			}

		  */
		  ?>

	  });


<?php if (detect_empty_cache_table () == true ){?>



const myTimeout = setTimeout(get_statistics_mounth, 400);


<?php }?>
}




function get_station_chat_mo()
{

	
	$('#Graph_Cricle_mo_holder').html("");
	$('#Graph_Cricle_mo_holder').html('<canvas id="Graph_Cricle_mo" width="400px" height="600px"></canvas>');

	
	var canvas = document.getElementById('Graph_Cricle_mo')
	canvas.width = canvas.width; 
	var ctx = document.getElementById('Graph_Cricle_mo').getContext('2d');
	var barDatafromJSON;
	var date_value = $('#Datepicker_graph').val();
	var Stations = new Array();
	var Stations_timesum = new Array();
	var Stations_collor = new Array()
	var j=0;
	$("#nodes_activity > tbody").html("");
	$("#nodes_activity > tfoot").html("");
	$.get( "get_statistics.php", { date: date_value,cahce_mouth :1} )
	  .done(function( data ) {
		  //console.log("chart");
		  console.log(data);
		 
		  var Stations_json = JSON.parse(data); 
		  var total_time_secunds =0;
		  var total_present =0;

	
		  for(var station in Stations_json.data)
		  {
			  console.log(Stations_json.data[j]);
			  Stations_timesum[j]=0;
			  Stations_timesum[j] = Stations_json.data[j].time;
			  total_time_secunds = total_time_secunds+ parseInt(Stations_json.data[j].time);
			  console.log(Stations_json.data[j].time);
			  Stations[j] =  Stations_json.data[j].call;
			  if(node_collors[Stations_json.data[j].call]["color"] != null)
			  {
			  	Stations_collor[j] = node_collors[Stations_json.data[j].call]["color"].trim();
			  }
			  else
			  {
			  	Stations_collor[j] = Hex_random_css_collor();
			  }
			  var preccent= (((Stations_json.data[j].time)/(86400*30)) * 100).toFixed(3);
			  total_present=total_present+parseFloat(preccent);
			  var preccent_network= (((Stations_json.data[j].time)/Stations_json.total_secounds) * 100).toFixed(3);
			  
			  $("#nodes_activity > tbody").append('<tr><td><span class="text-nowrap">'+Stations_json.data[j].call+'</span></td><td>'+Stations_json.data[j].Secound+"</td><td>"+preccent_network+"%</td><td class=\"d-none  d-md-table-cell\">"+preccent+"%</td></tr>");
			  j++;

			 
		  }
		  console.log(total_time_secunds);

		  $("#nodes_activity > thead ").html('<tr><th><?php echo _("Station")?></th><th><?php echo _("Uptime")?></th><th><?php echo _("Network Usage curent mouth")?></th><th  class="d-none  d-md-table-cell"><?php echo _("Usage last 30 days")?></th><th class="d-none  d-md-table-cell"></tr>');

		  
		  $("#nodes_activity > tfoot").append('<tr><td><?php echo _('Total')?></td><td>'+secondsToDayHMS(total_time_secunds)+'</td><td></td><td class=\"d-none  d-md-table-cell\" >'+total_present.toFixed(2)+'%</td><td class=\"d-none  d-md-table-cell\"></td></tr>');
		
	

		    var data = {
		    	    datasets: [{
		    	        data: Stations_timesum,
    					backgroundColor: Stations_collor,
    					borderColor:  Stations_collor
		    	    }],

		    	    // These labels appear in the legend and in the tooltips when hovering different arcs
		    	    labels: Stations
		    	};


		window.myBar = new Chart(ctx, {
			type: 'pie',
			data: data,
			options: {
				maintainAspectRatio: false,
				responsive: true,
				legend: {
					position: 'right'
					//display: true //This will do the task
				},
				title: {
					display: true,
					text: '<?php echo _('Station activity')?> '
				},

				
				tooltips: {
		            // Disable the on-canvas tooltip
		            enabled: true,
		            callbacks: {
		                label: function(tooltipItem, data) {
		                    var label = data.labels[tooltipItem.index];
		                    var talktime = data.datasets[0].data[tooltipItem.index];
		    
							
		                    if (label) {
		                        label += ': ';
		                    }
		                    //console.log(talktime);
		                    label += secondsToDHMS(talktime);
		                    return label;
		                }
		            }

		        }
			}
		});

	  });




	
}

function get_station_chat_year()
{

	
	$('#Graph_Cricle_year_holder').html("");
	$('#Graph_Cricle_year_holder').html('<canvas id="Graph_Cricle_year" width="400px" height="600px"></canvas>');

	
	var canvas = document.getElementById('Graph_Cricle_year')
	canvas.width = canvas.width; 
	var ctx = document.getElementById('Graph_Cricle_year').getContext('2d');
	var barDatafromJSON;
	var date_value = $('#Datepicker_graph').val();
	var Stations = new Array();
	var Stations_timesum = new Array();
	var Stations_collor = new Array()
	var j=0;
	$("#nodes_activity > tbody").html("");
	$("#nodes_activity > tfoot").html("");
	$.get( "get_statistics.php", { date: date_value,cahce_year :1} )
	  .done(function( data ) {
		  //console.log("chart");
	
		 
		  var Stations_json = JSON.parse(data); 
		  var total_time_secunds =0;
		  var total_present =0;

	
		  for(var station in Stations_json.data)
		  {
			  console.log(Stations_json.data[j]);
			  Stations_timesum[j]=0;
			  Stations_timesum[j] = Stations_json.data[j].time;
			  total_time_secunds = total_time_secunds+ parseInt(Stations_json.data[j].time);
			  console.log(Stations_json.data[j].time);
			  Stations[j] =  Stations_json.data[j].call;
			  if(node_collors[Stations_json.data[j].call]["color"] != null)
			  {
			  	Stations_collor[j] = node_collors[Stations_json.data[j].call]["color"].trim();
			  }
			  else
			  {
			  	Stations_collor[j] = Hex_random_css_collor();
			  }
			  var preccent= (((Stations_json.data[j].time)/(86400*365)) * 100).toFixed(3);
			  total_present=total_present+parseFloat(preccent);
			  var preccent_network= (((Stations_json.data[j].time)/Stations_json.total_secounds) * 100).toFixed(3);
			  
			  $("#nodes_activity > tbody").append('<tr><td><span class="text-nowrap">'+Stations_json.data[j].call+'</span></td><td>'+Stations_json.data[j].Secound+"</td><td>"+preccent_network+"%</td><td class=\"d-none  d-md-table-cell\">"+preccent+"%</td></tr>");
			  j++;

			 
		  }
		  console.log(total_time_secunds);

		  $("#nodes_activity > thead ").html('<tr><th><?php echo _("Station")?></th><th><?php echo _("Uptime")?></th><th><?php echo _("Network Usage year")?></th><th  class="d-none  d-md-table-cell"><?php echo _("Usage based on year")?></th><th class="d-none  d-md-table-cell"></tr>');

		  
		  $("#nodes_activity > tfoot").append('<tr><td><?php echo _('Total')?></td><td>'+secondsToDayHMS(Stations_json.total_secounds)+'</td><td></td><td class=\"d-none  d-md-table-cell\" >'+total_present.toFixed(2)+'%</td><td class=\"d-none  d-md-table-cell\"></td></tr>');
		
			console.log(Stations_json.total_secounds);

		    var data = {
		    	    datasets: [{
		    	        data: Stations_timesum,
    					backgroundColor: Stations_collor,
    					borderColor:  Stations_collor
		    	    }],

		    	    // These labels appear in the legend and in the tooltips when hovering different arcs
		    	    labels: Stations
		    	};


		window.myBar = new Chart(ctx, {
			type: 'pie',
			data: data,
			options: {
				maintainAspectRatio: false,
				responsive: true,
				legend: {
					position: 'right',
					 display: true //This will do the task
				},
				title: {
					display: true,
					text: '<?php echo _('Station activity')?> '
				},

				
				tooltips: {
		            // Disable the on-canvas tooltip
		            enabled: true,
		            callbacks: {
		                label: function(tooltipItem, data) {
		                    var label = data.labels[tooltipItem.index];
		                    var talktime = data.datasets[0].data[tooltipItem.index];
		    
							
		                    if (label) {
		                        label += ': ';
		                    }
		                    //console.log(talktime);
		                    label += secondsToDHMS(talktime);
		                    return label;
		                }
		            }

		        }
			}
		});

	  });

<?php if (detect_empty_cache_table() == true){?>

	get_statistics_year_tg();

<?php }?>	
}





function load_chart_delay()
{

	
	setTimeout(get_station_chat, 200);
	
	
}





function get_station_chat()
{

	
	$('#Graph_Cricle_holder').html("");
	$('#Graph_Cricle_holder').html('<canvas id="Graph_Cricle" width="400px" height="600px"></canvas>');

	
	var canvas = document.getElementById('Graph_Cricle')
	canvas.width = canvas.width; 
	var ctx = document.getElementById('Graph_Cricle').getContext('2d');
	var barDatafromJSON;
	var date_value = $('#Datepicker_graph').val();
	var Stations = new Array();
	var Stations_timesum = new Array();
	var Stations_collor = new Array()
	var j=0;
	$("#nodes_activity > tbody").html("");
	$("#nodes_activity > tfoot").html("");
	$.get( "get_statistics.php", { date: date_value,qrv :1} )
	  .done(function( data ) {
		  //console.log("chart");
		  console.log(data);
		 
		  var Stations_json = JSON.parse(data); 
		  var total_time_secunds =0;
		  var total_present =0;

	
		  for(var station in Stations_json.data)
		  {
			  console.log(Stations_json.data[j]);
			  Stations_timesum[j]=0;
			  Stations_timesum[j] = Stations_json.data[j].time;
			  total_time_secunds = total_time_secunds+ parseInt(Stations_json.data[j].time);
			  console.log(Stations_json.data[j].time);
			  Stations[j] =  Stations_json.data[j].call;
			  if(node_collors[Stations_json.data[j].call]["color"] != null)
			  {
			  	Stations_collor[j] = node_collors[Stations_json.data[j].call]["color"].trim();
			  }
			  else
			  {
			  	Stations_collor[j] = Hex_random_css_collor();
			  }
			  var preccent= (((Stations_json.data[j].time)/86400) * 100).toFixed(3);
			  total_present=total_present+parseFloat(preccent);
			  var preccent_network= (((Stations_json.data[j].time)/Stations_json.total_secounds) * 100).toFixed(3);
			  
			  $("#nodes_activity > tbody").append('<tr><td><span class="text-nowrap">'+Stations_json.data[j].call+'</span></td><td>'+Stations_json.data[j].Secound+"</td><td>"+preccent_network+"%</td><td class=\"d-none  d-md-table-cell\">"+preccent+"%</td><td  class=\"d-none  d-md-table-cell\">"+Stations_json.data[j].reciver+"</td></tr>");
			  j++;

			 
		  }
		  console.log(total_time_secunds);
		  $("#nodes_activity > tfoot").append('<tr><td><?php echo _('Total')?></td><td>'+secondsToDayHMS(total_time_secunds)+'</td><td></td><td class=\"d-none  d-md-table-cell\" >'+total_present.toFixed(2)+'%</td><td class=\"d-none  d-md-table-cell\"></td></tr>');


		  $("#nodes_activity > thead ").html('<tr><th><?php echo _("Station")?></th><th><?php echo _("Uptime")?></th><th><?php echo _("Network Usage 24 hour")?></th><th  class="d-none  d-md-table-cell"><?php echo _("Usage last 24 hour")?></th><th class="d-none  d-md-table-cell"><?php echo _("Most used receiver")?></th></tr>');
			
	

		    var data = {
		    	    datasets: [{
		    	        data: Stations_timesum,
    					backgroundColor: Stations_collor,
    					borderColor:  Stations_collor
		    	    }],

		    	    // These labels appear in the legend and in the tooltips when hovering different arcs
		    	    labels: Stations
		    	};


		window.myBar = new Chart(ctx, {
			type: 'pie',
			data: data,
			options: {
				maintainAspectRatio: false,
				responsive: true,
				legend: {
					position: 'top',
				},
				title: {
					display: true,
					text: '<?php echo _('Station activity')?> '+date_value
				},

				
				tooltips: {
		            // Disable the on-canvas tooltip
		            enabled: true,
		            callbacks: {
		                label: function(tooltipItem, data) {
		                    var label = data.labels[tooltipItem.index];
		                    var talktime = data.datasets[0].data[tooltipItem.index];
		    
							
		                    if (label) {
		                        label += ': ';
		                    }
		                    //console.log(talktime);
		                    label += secondsToDHMS(talktime);
		                    return label;
		                }
		            }

		        }
			}
		});

	  });




	
}
function numberconvert(n){
    return n > 9 ? "" + n: "0" + n;
}


function secondsToDHMS(seconds) {
    var totalSeconds = parseInt(seconds);
    if(totalSeconds >0)
    {
    	hours = Math.floor(totalSeconds / 3600);
    	totalSeconds %= 3600;
    	minutes = Math.floor(totalSeconds / 60);
    	seconds = totalSeconds % 60;
        
        
        return numberconvert(hours)+":"+numberconvert(+minutes)+":"+numberconvert(seconds);

    }
       else
            return "00:00:00";
            
}

function secondsToDayHMS(seconds) {
    var totalSeconds = parseInt(seconds);
    if(totalSeconds >0)
    {
    	days=  Math.floor(totalSeconds / 86400);
    	totalSeconds %= 86400;
    	hours = Math.floor(totalSeconds / 3600);
    	totalSeconds %= 3600;
    	minutes = Math.floor(totalSeconds / 60);
    	seconds = totalSeconds % 60;
        
        
        return (days)+":"+numberconvert(hours)+":"+numberconvert(+minutes)+":"+numberconvert(seconds);

    }
       else
            return "00:00:00";
            
}




function prosess_json(url)
{

  $.getJSON(url, function(result){
 // console.log(result);
    
//	start_data(result.uri.signalEventStream);


	
	
	  for(var k in result.rx) {
			
	
			var name =result.antenna[result.rx[k].antenna].name;
			add_repeater_node(result.antenna[result.rx[k].antenna].location.lat, result.antenna[result.rx[k].antenna].location.lon,name,k);
			setmap(result.antenna[result.rx[k].antenna].location.lat, result.antenna[result.rx[k].antenna].location.lon,10);

		
		}
    
  });

}
// Crearl map and add station with filter

function prosess_json_filter(url,filter)
{

  $.getJSON(url, function(result){

	  for(var k in result.rx) {
			
	
			var name =result.antenna[result.rx[k].antenna].name;
			if(filter == "" || result.rx[k].Filter == filter)
			{
				//console.log(result.rx[k]);
				add_repeater_node(result.antenna[result.rx[k].antenna].location.lat, result.antenna[result.rx[k].antenna].location.lon,name,k);
				setmap(result.antenna[result.rx[k].antenna].location.lat, result.antenna[result.rx[k].antenna].location.lon,10);
			}
		
		}
//		start_data(result.uri.signalEventStream);
    
  });

}



function setmap(lat,lon,z)
{
         map.setView(new ol.View({
               center: ol.proj.transform([lon,lat], 'EPSG:4326', 'EPSG:3857'),
               zoom: z,minZoom: 2,maxZoom: 19}));
}

function setmap_noTransform(lon,lat,z)
{		
		var position =[lon,lat];
         map.setView(new ol.View({
               center: position,
               zoom: z,minZoom: 2,maxZoom: 19}));
}



var Lock_show =0;
var coverigeGroup;


function open_select_map()
{

	var popupWindow = parent.window.open("select_map_stations.php", "mozillaWindow", 'width=800,height=800',"popup" );
	if (popupWindow.addEventListener) {
	popupWindow.addEventListener('message', function(event) {
		    console.log("Message received from the child: " + event.data); // Message received from child
			var data =  event.data.toString();
			console.log(data.split(","));
			
			show_covige_stations(data.split(","))
			 popupWindow.close();
		  });
	}
	
}



function idn_loocup(site)
{
	var objet_id = vectorSource.getFeatures();

	for(var id in objet_id )
	{

		var site_idn = objet_id[id].id_;
		if(site_idn == ('stn_'+site))
		{
			return true;
		}
		if( site_idn.includes(site))
		{
			return true;
		}

	}


	return false;

	
}
function idn_loocup_select(site,array)
{


	for(var id in array )
	{

		var site_idn = array[id];


		if(site.includes(site_idn) )
		{
			return true;
		}

	}


	return false;

	
}


function show_covige()
{
	remove_covige();
	if(Lock_show == 0)
	{
		Lock_show =1;

		coverigeGroup = new ol.layer.Group({
            layers: [],
            name: 'coverige'
        });

		<?php

				$result = mysqli_query($link, "SELECT * FROM `covrige` ");

				// Numeric array

				// Associative array
				while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				    echo "if(idn_loocup('".$row["Name"]."')) {";
				    
				    echo "" . $row["Radiomobilestring"] . "}";

		
				}

				?>

		
				map.addLayer(this.coverigeGroup);
		
	}
}
function remove_covige()
{
	this.map.removeLayer(this.coverigeGroup);
	Lock_show  =0;
}

function show_covige_stations(arrays)
{

	
	remove_covige();
	if(Lock_show == 0)
	{
		Lock_show =1;
		

		coverigeGroup = new ol.layer.Group({
            layers: [],
            name: 'coverige'
        });

		console.log(arrays);
		
		<?php

				$result = mysqli_query($link, "SELECT * FROM `covrige` ");

				// Numeric array

				// Associative array
				while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				    echo "if(idn_loocup_select('".$row["Name"]."',arrays)) {";
				    
				    echo "" . $row["Radiomobilestring"] . "}";

		
				}

				?>

		
				map.addLayer(this.coverigeGroup);
		
	}
}









function load_Recivers_html()
{
	$.get( "Recivers.php", function( data ) {
  		$( "#Recivers" ).html( data );
 
	});
}
function apeend_Recivers_html(id)
{
	$.get( "Recivers.php?id="+id, function( data ) {
  		$( "#Recivers" ).append( data );
 
	});
}
// Idientify station by idn 
var station_identifire = new Array();
function add_tx_station()
{
	$.getJSON( "<?php echo $serveradress ?>", function( data ) {


		for(var k in data.nodes){
	 
	 
	
		    if(data.nodes[k].hidden == true)
		    {
			    console.log(data.nodes[k]);
		    	delete data.nodes[k];
	
		    }
		    if ( k.includes("/")) {
				var new_call = k.replace("/",'-');
		    	data.nodes[new_call] = data.nodes[k]; 
		        delete data.nodes[k];
		    }
		    
		}
		
		for(var k in data.nodes){
		    for(var qth in data.nodes[k].qth){
		    	//console.log(k+" - "+data.nodes[k].qth[qth].name);
		        for(var qth1 in data.nodes[k].qth[qth].tx){
			        		        
                    var lat =parseFloat(data.nodes[k].qth[qth].pos.lat);

                    var lon =parseFloat(data.nodes[k].qth[qth].pos.long);

                    if(isNaN(parseFloat(lat)) == false && isNaN(parseFloat(lon)) == false )
                    {
                    
        				var name = k+" "+data.nodes[k].qth[qth].tx[qth1].name;
        				var talkgroup =data.nodes[k].tg;
        				var idn=k+data.nodes[k].qth[qth].tx[qth1].name;
        				idn =idn.replace(/ /g,"_");
        				station_identifire[idn] =k;
        			
        				//console.log(name);
        				
        				
        				
        
        					if(!valudate_if_exist(lon,lat))
            				{
            					map_lon_lat[map_pos_i] = new Array();
            					map_lon_lat[map_pos_i]['lat'] = lat;
            					map_lon_lat[map_pos_i]['lon'] = lon;
            					map_pos_i++;
        						add_repeater_transmiter(lat,lon,name,idn,talkgroup)
            				}
            				
        				// tempoary fix if useing mor than 1 transmitter on same qth
        				setmap(lat, lon,8);
        				break;
                    }
		        }

			}
			console.log(station_identifire);	
		}

		//add_repeater_node()

		<?php if($default_long != "" && $default_lat !=""){?>
			setmap(<?php echo $default_lat?>, <?php echo $default_long?>,<?php echo $default_zoom?>);
		<?php }?>
		});
	
}

function update_tx_station_loop(data)
{

	  if (data === undefined) 
      {
		    return 'Undefined value!';
       }


	
//	$.getJSON( "<?php echo $serveradress ?>", function( data ) {
	
		for(var k in data.nodes){
			
		    if(data.nodes[k].hidden == true)
		    {
		    	delete data.nodes[k];
		    	
		    }
		    if ( k.includes("/")) {
				var new_call = k.replace("/",'-');
		    	data.nodes[new_call] = data.nodes[k]; 
		        delete data.nodes[k];
		    }
		    
		}
		
		for(var k in data.nodes){


			
		    for(var qth in data.nodes[k].qth){
		        for(var qth1 in data.nodes[k].qth[qth].tx){
				var lat =parseFloat(data.nodes[k].qth[qth].pos.lat)
				var lon =parseFloat(data.nodes[k].qth[qth].pos.long);


				var talkgroup =data.nodes[k].tg;
				
				var name = k+" "+data.nodes[k].qth[qth].tx[qth1].name;
				var idn=k+data.nodes[k].qth[qth].tx[qth1].name;
				var active =data.nodes[k].qth[qth].tx[qth1].transmit;
				if(active == null)
				{
					active = false;
				}
				idn =idn.replace(/ /g,"_");

				if(isNaN(parseFloat(lat)) == false && isNaN(parseFloat(lon)) == false )
				  update_tx_station(lat,lon,idn,talkgroup,name,active,data.nodes[k].isTalker);
		        	

		        }
				var count_rx =0;
				for(var q in data.nodes[k].qth[qth].rx)
			    {
		        	count_rx++;
			    }	
        

		        for(var qth1 in data.nodes[k].qth[qth].rx)
		        {
		        
        			var lat =parseFloat(data.nodes[k].qth[qth].pos.lat)

    				
        			var lon =parseFloat(data.nodes[k].qth[qth].pos.long);
        			var name = k+" "+data.nodes[k].qth[qth].rx[qth1].name;
        			var talkgroup =data.nodes[k].tg;
        			//var idn=k+data.nodes[k].qth[qth].rx[qth1].name;
        			var idn=k+qth1;
        			idn =idn.replace(/ /g,"_");
        			
 //       			console.log(idn+" "+lat+" "+lon);
        			var value =0;
                   	var qth_name =data.nodes[k].qth[qth].rx[qth1].name;
                   	var rx_active =data.nodes[k].qth[qth].rx[qth1].active;
                   	var rx_sql =data.nodes[k].qth[qth].rx[qth1].sql_open;
                   	value =data.nodes[k].qth[qth].rx[qth1].siglev;
                   	var Freqvensy =data.nodes[k].qth[qth].rx[qth1].freq;

                   	if(value == null)
                   	{
                   		value=0;
                   	}
    
    			
        			var sql =""
        			if(rx_sql== true)
        			{
        				sql='open'
        			}
        			if(rx_active== true)
        			{
        				sql='active'
        			}
        			if(count_rx > 1 && sql != "")
        			{
        				//var group_idn = k+data.nodes[k].qth[qth].name;
        				var group_idn=k+data.nodes[k].qth[qth].rx[qth1].name;
        				group_idn =group_idn.replace(/ /g,"_");
        				if(Barsource.getFeatureById(group_idn) != null)
        				{
                			update_text_byid(group_idn,value.toString(),sql);
                			update_bar(group_idn,value.toString(),sql);
            			}
            			break;
            	
        			}
        			else if(count_rx > 1)
        			{
        				//var group_idn = k+data.nodes[k].qth[qth].name;
        				var group_idn=k+data.nodes[k].qth[qth].rx[qth1].name;
        				group_idn =group_idn.replace(/ /g,"_");
        				if(Barsource.getFeatureById(group_idn) != null)
        				{
                			update_text_byid(group_idn,value.toString(),sql);
                			update_bar(group_idn,value.toString(),sql);
        				}
        				            			
						// DO Nothing 
						
        			}
        			else
        			{
        				if(Barsource.getFeatureById(idn) != null)
        				{
                			update_text_byid(idn,value.toString(),sql);
                			update_bar(idn,value.toString(),sql);
        				}
        			}
        			
	        	}

			}	
		}
		Barsource.refresh()
		vectorSource.refresh()

		//add_repeater_node()



//		});
	//if(kill_loop == 0)
	  //instervalls = setTimeout(update_tx_station_loop, 500);
}


</script>


					</div>


				</div>






				<!-- Menu Toggle Script -->
				<script>


var date_loaded ="";
				

function get_audio_date()
{

	var output =$('#datepicker').val();

    if(date_loaded != output)
    {
    	date_loaded = output;
    	get_audio_from_date(output )
    }
	
}

function change_day_next()
{

    var date = $('#Datepicker_graph').datepicker('getDate');
    date.setDate(date.getDate() +1)
    $('#Datepicker_graph').datepicker('setDate', date);
    get_statistics();
    get_statistics_hour();

 <?php if (detect_empty_cache_table() == true){?>


 	setTimeout(change_data_date, 400);
 	
    
<?php }?>

}
function change_data_date()
{

 <?php if (detect_empty_cache_table() == true){?>


 $('#tab_plane_statistics > div').each(function () {
		console.log($(this).hasClass("active"));
	  if($(this).hasClass("active") == true )
		  { 

    		  if($(this).attr('id') == 'menu_year')
    		  {
    			  get_year_static();
    
    		  }
    
    		  if($(this).attr('id') == 'menu_month')
    		  {
    			   get_statistics_month();
    		  }

    		  if($(this).attr('id') == 'menu_hour')
    		  {
        	
    			  load_chart_delay();
    		  }

    		  

		  
		  }
	  
});

 


 

    
<?php }?>

	
	
}

function change_day_prew()
{

	var date = $('#Datepicker_graph').datepicker('getDate');
	date.setDate(date.getDate() -1)
	$('#Datepicker_graph').datepicker('setDate', date);
    get_statistics();
    get_statistics_hour();

 <?php if (detect_empty_cache_table() == true){?>
    
 	change_data_date();
    
<?php }?>

}
function bind_key_statistics()
{


	$(document).keydown(function(e) {
	    switch(e.which) 
	    {
	        case 37: // left
	        	change_day_prew()
	        break;

	        case 38: // up
	        break;

	        case 39: // right
	        	change_day_next()
	        break;

	        case 40: // down
	        break;

	        default: return; // exit this handler for other keys
	    }
	    e.preventDefault(); // prevent the default action (scroll / move caret)
	});
	
}

function fnExcelexport(table)
{
    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j=0;
    tab = document.getElementById(table); // id of table

    for(j = 0 ; j < tab.rows.length ; j++) 
    {     
        tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
        //tab_text=tab_text+"</tr>";
    }

    tab_text=tab_text+"</table>";
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE "); 

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
        txtArea1.document.open("txt/html","replace");
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus(); 
        sa=txtArea1.document.execCommand("SaveAs",true,"export.xls");
    }  
    else                 //other browser not tested on IE 11
        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

    return (sa);
}






   
	
    </script>

				<div class="tab-pane" id="LoginTab">
					<div class="wrapper1 ">
						<div id="formContent">
							<!-- Tabs Titles -->

		



						</div>
					</div>
				</div>
             <div id="stationinfor" class="tab-pane">
    			
    		<nav class="navbar navbar-expand-sm navbar-light  bg-light" style="background-color: #e3f2fd;">
		
				<a class="navbar-brand" href="#"><?php  echo _('Station infromation')?> 
				
				
				<ul class="navbar-nav mr-auto mt-2 mt-lg-0">


      
         <li class="nav-item d-none d-lg-inline-flex">
        
             
        <a class="nav-link  " href="#" id="navbarDropdownMenuLink" onclick="PrintElem('print_div','')" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <i class="fas fa-print"></i>
          <?php echo _('Print')?>        </a>
             
        </li>

        </li>
        
    
        
        
        
            		
        
        
        
   
      
      
      
    </ul>
    
    
				
				
				
				 <select class="selectpicker w-25" id="Pricker_station_monitor"  onchange="Load_station_intofmation(this.value)" >
				 <option value=""><?php echo _('Select station')?></option>

  <?php 
      			$result = mysqli_query($link, "SELECT * FROM `Infotmation_page` ORDER BY `Station_Name` ASC ");

    			// Numeric array

    			// Associative array
    			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    ?>
    <option value="<?php echo $row["Station_Name"]?>" ><?php echo $row["Station_Name"] ?></option>        
    <?php }?>
            
                 </optgroup>
                                </select>


 
    

    
		

      
			</nav>
			<div id="station_info_html_data"></div>
    			
    			</div>

            <div id="register" class="tab-pane">
            
            
            <script type="text/javascript">


            function create_user()
            {
            	var usern = $('#usern1').val();
            	var pass = $('#passwordareg').val();
            	var pass1 = $('#password_confirm').val();

            	if(pass != pass1)
            	{
            		alert("<?php echo _('Passwords aren same')?>")
            		return false;
            		
            	}

            	if((usern != "" && pass != "" ) && (pass == pass1))
            	{
                	$.post( "admin/user_action.php", $( "#register_user" ).serialize() )
                	.done(function( data ) {

						if(data == "-1")
						{
							alert("<?php echo _('User already exist!')?>")
						}
						else
						{
                			alert("<?php echo _('User is creadted!')?>")
						}
                		
                
                	});
            	}
            	

            	return false;
            }


            

            </script>
            
                    <nav class="navbar navbar-expand-sm navbar-light  bg-light" style="background-color: #e3f2fd;">
        		
        		<a class="navbar-brand" href="#"><?php echo _('Register')?></a>
        		
			  </nav>

			<form class="form-horizontal" id="register_user" action='' method="POST" onsubmit="return create_user()">
						  
          <div class="form-group">
                <label class="control-label col-sm-2" for="Firstname"><?php echo  _('Firstname')?>:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="Firstname" name="Firstname" placeholder="<?php  echo _('jay')?>">
                </div>
          </div>                                    
                           
          <div class="form-group">
                <label class="control-label col-sm-2" for="lastname"><?php echo  _('Lastname')?>:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="lastname" name="lastname" placeholder="<?php  echo _('Shmit')?>">
                </div>
          </div>                                    
                                                      
                           			  
			  
			  

          
            <div class="form-group">
                <label class="control-label col-sm-2" for="username"><?php echo  _('Username')?>:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="username" name="username" placeholder="SA%RME">
                </div>
          </div>      
          
          <input type="hidden"  id="registernewuser" name="registernewuser" value="1">
          
           <div class="form-group">
                <label class="control-label col-sm-2" for="E"><?php echo  _('E-mail')?>:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="Email" name="Email" placeholder="<?php echo _('info@test.nu') ?>">
                </div>
          </div>  
                         
          <div class="form-group">
                <label class="control-label col-sm-2" for="password"><?php echo  _('Password')?>:</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="passwordareg" name="password" placeholder="<?php echo _('Password should be at least 8 characters') ?>">
                </div>
          </div>                                    
                             
          <div class="form-group">
                <label class="control-label col-sm-2" for="password_confirm"><?php echo  _('Password (Confirm)')?>:</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="<?php  echo _('Please confirm password')?>">
                </div>
          </div>                                    
                                  

            <div class="form-group">
              <!-- Button -->
    
               
                 <button class="btn btn-success" onclick=" document.getElementById('register_user').reset(); "><?php echo _('Clear')?></button>
                 <button class="btn btn-success"><?php echo _('Register')?></button>
            </div>


        	</form>
                            
                            
            			
            	</div>		
   

				<div class="tab-pane " id="Statistics">
			
			
    			<div class="row">
    			<div class="col-12">

    		</div>
    		    		
				</div>
        	  <div class="col-12">
               <nav id="ssas" class="navbar navbar-default ">
               
        
                       <ul class="nav  navbar-expand">
                      	<li class="nav-link  active"><a data-toggle="tab" onclick="$('#ssas a.active').removeClass('active');get_statistics();" href="#dastaty"><i class="far fa-circle"></i> <?php echo _("Day")?></a></li>
                      	<li class="nav-link "><a data-toggle="tab" onclick="$('#ssas a.active').removeClass('active');get_statistics_hour();get_station_chat();" href="#menu_hour"><i class="far fa-circle"></i> <?php echo _("Hour")?></a></li>
                      	<li class="nav-link "><a data-toggle="tab" onclick="$('#ssas a.active').removeClass('active');get_statistics_month()" href="#menu_month"><i class="far fa-circle"></i> <?php echo _("Month")?></a></li>
                      	<li class="nav-link "><a data-toggle="tab" onclick="$('#ssas a.active').removeClass('active');get_year_static()" href="#menu_year"><i class="far fa-circle"></i> <?php echo _("Year")?></a></li>
    
                       </ul> 
                   
        	
    			<div class="nav navbar-nav navbar-right">
					
					<div class="form-group">
					<div class="row">
    					<div class="col-2 col-xl-1">
       					  <button class="prev-day btn btn-outline-secondary"  onclick="change_day_prew()" id="prev-day"><i class="fa fa-angle-left" aria-hidden='true' ></i></button>
       					</div>
       					<div class="col-8 col-xl-10">
       					  <input style="margin-left: 5px" type="text" id="Datepicker_graph" value="<?php echo date("Y-m-d")?>" onchange="get_statistics();get_statistics_hour();change_data_date();" class="form-control" >
        				</div>
        				<div class="col-2 col-xl-1">
    					  <button style="margin-left: " class='next-day btn btn-outline-secondary' onclick="change_day_next()" ><i class='fa fa-angle-right' aria-hidden='true'></i></button>
    					</div>
					</div>
					 </div>
    			</div>
                   
            </nav>
            </div>
            <br />
        
             


        		<div class="row">
				<div class="tab-content col-md-12" id="tab_plane_statistics">
				
				
     			<div id="dastaty" class="tab-pane in active w-100  ">
                    <div class="container-fluid">
                        <div class="row">
                        	<div class="col-md-6">
                        	
        		<div class="card shadow mb-4">
                	<div class="card-header py-3 text-white bg-dark">
                  		<h6 class="m-0 font-weight-bold "><?php echo _('Talkgroups')?></h6>
           		 	</div>
                    <div class="card-body">
						
					
        				<div style="width: 100%; height: 600px;" id="canvas_grap_holder">
        					<canvas id="Graph" width="800px" height="600px"></canvas>
        				</div>
        				
                				
                				    			
                    </div>
              	</div>
              </div>
              
              
              
              
                        	
                        
                				
                				
               
                			
                			
                	<div class="col-md-6"> 
                	
                	        		<div class="card shadow mb-4">
                	<div class="card-header py-3  text-white bg-dark">
                  		<h6 class="m-0 font-weight-bold "><?php echo _('Nodes')?></h6>
           		 	</div>
                    <div class="card-body" style="min-height:540px;">
                    
                    
                	
                	
                		<div class="" id="Graph_Cricle_holder">
                				<canvas id="Graph_Cricle" width="400px" height="400px"></canvas>
                		</div>	
                		
                    </div>
              	</div>
     
              	
                		
                    </div>
                    		
                    		
                    		
                    	 </div>
                	 </div>
            	 </div>
            	 
      			<div id="menu_hour" class="tab-pane  in  w-100  ">
                    <div class="container-fluid">
                        <div class="row">
                        	<div class="col-md-12">
                        	<div class="card shadow mb-4">
                     
                    	     	<div class="card-header py-3 text-white bg-dark ">
                    	     	<div class="row">
                    	     	   	<div class="col-8">
                  					<h6 class="m-0 font-weight-bold "><?php echo _('Hour chart')?></h6>
                  					</div>
                  					<div class="col-4">
                  					
                  						<select class="selectpicker float-right col-11" id="filterpicker_repeater_time" onchange="set_repater_statics_time()" >
                  					              
                               		  <option value=""><?php echo _('No Repeater Filter')?></option>
                                 		 <optgroup label="<?php echo _('Repeater')?>">
  <?php 
      			$result = mysqli_query($link, "SELECT * FROM `RefletorStations` WHERE Callsign != '' ORDER BY `Callsign` ASC");

    			// Numeric array

    			// Associative array
    			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    
    			    if(return_diff_to_darkness(($row["Collor"])) <100 && return_diff_to_darkness($row["Collor"]) >0)
    			    {
    			        $color_text ="color:white;";
    			        
    			    }
    			    else
    			    {
    			        $color_text ="";
    			        
    			    }
    			        
    			 
    			    
    ?>
    
    <option value="<?php echo $row["Callsign"]?>" style="background-color: <?php echo $row["Collor"]?>;<?php echo $color_text?> "><?php echo $row["Callsign"]; ?></option>        
    <?php }?>
            
                                  </optgroup>
                                </select>
            </div>
            </div>
                                
                                
                                
                  					
           		 				</div>
           		 	
           		 	
                				<div style="width: 97%; height: 500px;" id="canvas_grap_holder1">
                					<canvas id="Graph1" width="400px" height="400px"></canvas>
                					
                				</div>	
                				<br />
                				</div>
                			</div>

                    	 </div>
                	 </div>
            	 </div>
            	 <?php 
            	 
            	 /*
            	  * 
            	  * Mouth statistics.
            	  * 
            	  */
            	 
            	 
            	 
            	 ?>
            	 
            	 
            	 <div id="menu_month" class="tab-pane  in  w-100  ">
            	 	<div class="container-fluid">
            	 	
            	 	<div class="col-md-12">
                        	<div class="card shadow mb-4">
                    	     	<div class="card-header py-3 text-white bg-dark">
                  			
	                    	    <div class="row">
                    	     	
                    	     	
                    	     	   	<div class="col-8">
                  								<h6 class="m-0 font-weight-bold "><?php echo _('Month chart')?></h6>
                  					</div>
                  					<div class="col-4">
                  					
                  						<select class="selectpicker float-right col-11" id="filterpicker_repeater_mouth" onchange="set_repater_statics_mounth()" >
                  					              
                               		  <option value=""><?php echo _('No Repeater Filter')?></option>
                                 		 <optgroup label="<?php echo _('Repeater')?>">
  <?php 
      			$result = mysqli_query($link, "SELECT * FROM `RefletorStations` WHERE Callsign != '' ORDER BY `Callsign` ASC");

    			// Numeric array

    			// Associative array
    			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    
    			    if(return_diff_to_darkness(($row["Collor"])) <100 && return_diff_to_darkness($row["Collor"]) >0)
    			    {
    			        $color_text ="color:white;";
    			        
    			    }
    			    else
    			    {
    			        $color_text ="";
    			        
    			    }
    			        
    			 
    			    
    ?>
    
    <option value="<?php echo $row["Callsign"]?>" style="background-color: <?php echo $row["Collor"]?>;<?php echo $color_text?> "><?php echo $row["Callsign"]; ?></option>        
    <?php }?>
            
                                  </optgroup>
                                </select>
            </div>
            </div>
            
            
            
           		 				</div>
           		 	
           		 	
                				<div style="width: 97%; height: 500px;" id="canvas_grap_holder2"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div><canvas id="Graph_month" width="1217" height="500" style="display: block; width: 1217px; height: 500px;" class="chartjs-render-monitor"></canvas></div>	
                				<br>
                				</div>
                			</div>
                			
                			
                			
            	 	
            	 
   
   
   <?php 
   
   /*
    * New feald for statistics 
    */
   
   ?>
   
   
   <?php if (detect_empty_cache_table() == true){?>


            <div class="row">
                 <div class="col-md-12">
                        	
        				<div class="card shadow mb-4">
                			<div class="card-header py-3 text-white bg-dark">
                  				<h6 class="m-0 font-weight-bold "><?php echo _('Talkgroups')?></h6>
           		 			</div>
                    	<div class="card-body">
						
					
        				<div style="width: 100%; height: 600px;" id="Graph_mo_grap_holder">
        					<canvas id="Graph_mo" width="800px" height="600px"></canvas>
        				</div>
        				
                				
                				    			
                    </div>
              	</div>
              </div>
             
        </div>
		
		<div class="row">
			
			
        	<div class="col-md-12"> 
        	
        	        		<div class="card shadow mb-4">
        						<div class="card-header py-3  text-white bg-dark">
          							<h6 class="m-0 font-weight-bold "><?php echo _('Nodes')?></h6>
   		 						</div>
            					<div class="card-body" style="min-height:540px;">

        						<div class="" id="Graph_Cricle_mo_holder">
        							<canvas id="Graph_Cricle_mo" width="400px" height="400px"></canvas>
        						</div>	
        		
            				</div>
      	</div>
              	
              	
   </div>           	

	</div>
	</div>
   
   <?php }else{?>
   </div>
   
   <?php }?>
            	 
            	 </div>
            	 
       			<div id="menu_year" class="tab-pane  in  w-100  ">
                    <div class="container-fluid">
                        <div class="row">
                        

                        	<div class="col-md-8">
                        	<div class="card shadow mb-4" style="min-height: 610px;">
                        	
                				<table class="table" >
                				  <thead class="thead-dark">
    <tr>
      <th scope="col">

                       	
                        	
                        	<select class="selectpicker" id="filterpicker_repeater_year" onchange="get_year_static()">
                                 <option value=""><?php echo _('No Repeater Filter')?></option>
                                  <optgroup label="<?php echo _('Repeater')?>">
  <?php 
      			$result = mysqli_query($link, "SELECT * FROM `RefletorStations` WHERE Callsign != '' ORDER BY `Callsign` ASC");

    			// Numeric array

    			// Associative array
    			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    
    			    if(return_diff_to_darkness(($row["Collor"])) <100 && return_diff_to_darkness($row["Collor"]) >0)
    			    {
    			        $color_text ="color:white;";
    			        
    			    }
    			    else
    			    {
    			        $color_text ="";
    			        
    			    }
    			        
    			 
    			    
    ?>
    
    <option value="<?php echo $row["Callsign"]?>" style="background-color: <?php echo $row["Collor"]?>;<?php echo $color_text?> "><?php echo $row["Callsign"]; ?></option>        
    <?php }?>
            
                                  </optgroup>
                                </select>
         </th>    
         <th scope="col">
                         	<select class="selectpicker" id="filterpicker_talgroup_year" onchange="get_year_static()">
                                 <option value=""><?php echo _('No Talkgroup Filter')?></option>
                                  <optgroup label="<?php echo _('Talkgroup')?>">
                                  
                 <?php 
    			$result = mysqli_query($link, "SELECT * FROM `Talkgroup` ORDER BY `Talkgroup`.`TG` ASC");

    			// Numeric array

    			// Associative array
    			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    			    
    			    
    			    if(return_diff_to_darkness(($row["Collor"])) <100 && return_diff_to_darkness($row["Collor"]) >0)
    			    {
    			        $color_text ="color:white;";
    			        
    			    }
    			    else
    			    {
    			        $color_text ="";
    			        
    			    }
    			    
    			    
    			    
    ?>
    <option value="<?php echo $row["TG"]?>" style="background-color: <?php echo $row["Collor"]?> ;<?php echo $color_text;?>"><?php echo $row["TG"] ?>		<?php echo  $row["TXT"] ?></option>        
    <?php }?>


                                  </optgroup>
                                </select>        
         </th>         
       </tr>
  </thead>                              
                                </table>
                             

                				<div style="position: relative; height:500px!important;" id="canvas_grap_holder3">
                					<canvas class='img-responsive'  width="700px" height="500px" id="Graph3"></canvas>
           

                					
                					
                				</div>
                				<br />
<!-- 		end of contener -->
		   </div>
                		    </div>
                				<div class="col-md-4">
                		<div class="card shadow mb-4">			
                                        	
                                        				
                				
                				<table class="table" id="toplist_table">
                				  <thead class="thead-dark">
    <tr>
      <th scope="col" colspan="2"><?php echo _('Toplist 10 days')?></th>

    </tr>
  </thead>
  
  <thead>
    <tr>
      <th scope="col"><?php echo _('Date')?></th>
      <th scope="col"><?php echo _('Talk time')?></th>

    </tr>
  </thead>
    <tbody>
    </tbody>

</table>


                				
    	</div>
	</div>
 </div>            		


   <?php if (detect_empty_cache_table() == true){?>

<div class="row">
                 <div class="col-md-12">
                        	
        				<div class="card shadow mb-4">
                			<div class="card-header py-3 text-white bg-dark">
                  				<h6 class="m-0 font-weight-bold "><?php echo _('Talkgroups')?></h6>
           		 			</div>
                    	<div class="card-body">
						
					
        				<div style="width: 100%; height: 600px;" id="Graph_ytg_grap_holder"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div><canvas id="Graph_tg_grap_mo" style="display: block; width: 1481px; height: 600px;" class="chartjs-render-monitor" width="400" height="600"></canvas></div>
        				
                				
                				    			
                    </div>
              	</div>
              </div>
             
</div>
        
        
        



<div class="row">
			
			
	<div class="col-md-12"> 
        	
        	        		<div class="card shadow mb-4">
        						<div class="card-header py-3  text-white bg-dark">
          							<h6 class="m-0 font-weight-bold "><?php echo _('Nodes')?></h6>
   		 						</div>
            					<div class="card-body" style="min-height:540px;">

        						<div class="" id="Graph_Cricle_year_holder">
        							<canvas id="Graph_Cricle_year" width="400px" height="400px"></canvas>
        						</div>	
        		
            				</div>
      	</div>
      	
      	
      	


                    	
	 </div>
</div>    

<?php }?>       	 
    
    
    
        


<div class="row">
    				<div class="col-md-12">
    					&nbsp;
    				</div>
	</div>
		</div>
			</div>
			
			
	<div class="col-md-12" id="table">
    			
    			<div class="card shadow mb-4">
           
                    
    				<table id="nodes_activity" class="table" >
    				<thead class="thead-dark">
    				  <tr>
    				    <th><?php echo _("Station")?></th>
    				    <th><?php echo _("Uptime")?></th>
    				    <th><?php echo _("Network Usage 24 hour")?></th>
    				    <th  class="d-none  d-md-table-cell"><?php echo _("Usage last 24 hour")?></th>
    				    <th class="d-none  d-md-table-cell">
    				    <?php echo _("Most used receiver")?></th>
				      </tr>
				    </thead>
    				<tbody class="tbody"></tbody>
    				<tfoot style ="font-weight: bold;"></tfoot>
    				
    				</table>
    			</div>
    			</div>	


</div> 
</div> 
</div> 
    			
    			<div class="tab-pane " id="Table_ctcss">
    			<?php 
    			$noheader =1;
    			
    			//include 'ctcss_map_table.php'?>
    			</div>
    			
    			<div class="tab-pane " id="Last_heard_page">
    			<?php 
 
    			
    			include 'las_call_box.php'?>
    			</div>    			
    
    
    
    


			
 
    			
    			
				
			</div>
			<!-- /#page-content-wrapper -->
			



		</div>
		<!-- /#wrapper -->
</div>
</div>
 <div>
 



 
 
  <div>
</body>

</html>
