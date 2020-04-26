<?php
header('Access-Control-Allow-Origin: <?php echo $serveradress ?>');
include "config.php";
include 'function.php';

set_laguage();


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title> SVX Portal <?php echo $sitename ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
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


<link rel="stylesheet"
	href="jquery-ui.css">
<scripT src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"
	integrity="sha256-0YPKAwZP7Mp3ALMRVB2i8GXeEndvCq3eSl/WsAl1Ryk="
	crossorigin="anonymous"></script>

<script
	src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js"></script>
<link rel="stylesheet"
	href="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/css/ol.css">
<!-- fontawesome.com iconpac -->
<link href="./fontawesome/css/all.css" rel="stylesheet">
<!--load all styles -->
<script src="./js/chart/Chart.min.js"></script>
<script src="./js/div_recivers.js"></script>
<?php include "div_reciver.php";?>


<script type="text/javascript">
var refelktor_address="<?php echo $serveradress ?>";
//<![CDATA[
$(document).ready(function(){
call_svxrefelktor();
add_node_collors();
load_reflector();
generate_coulor();

	$( "#datepicker" ).datepicker({firstDay: 1, dateFormat: 'yy-mm-dd' });


		var myPlaylist = new jPlayerPlaylist({
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

		$( "#Datepicker_graph" ).datepicker({firstDay: 1, dateFormat: 'yy-mm-dd' });

		

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
	$('#Reciverbars_player').append('<p>'+Json_data.Nodename+'</p><canvas id="canvpr"></canvas><br/>');
	create_bar_rx(Json_data.Siglev,'canvpr',true);
    
    $('#signalpressent').html(Json_data.Siglev);

		for(var k in Json_data.Subreciver){

			$('#Reciverbars_player').append('<p>'+Json_data.Subreciver[k]['Nodename']+'</p><canvas id="canvp'+k+'"></canvas><br/>');
			create_bar_rx(Json_data.Subreciver[k]['Siglev'],'canvp'+k,false);
			
		}

	}
    

});


});
function create_bar_rx(value,element,rx_sql)
{
    var canvas = document.getElementById(element);
    var context = canvas.getContext('2d');
    width= 0.2 *window.innerWidth
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

function call_svxrefelktor() {
$.getJSON( "<?php echo $serveradress ?>", function( data ) {
	//console.log(data);
	for(var k in data.nodes){
		
	    if(data.nodes[k].hidden == true)
	    {
	    	delete data.nodes[k];
	    	
	    }
	}

	
	

$('#Reflektortable').html('<th><?php echo _("Callsign")?></th><th><?php echo _("TG")?></th><th><?php echo _("Is talker")?></th><th><?php echo _("Monitored TGs")?></th><th><?php echo _("Talk time")?></th><th><?php echo _("Active RX")?></th>');
for(var k in data.nodes){
	var text =" ";
	for(var nodes in data.nodes[k].monitoredTGs){
	
	   text = text  +data.nodes[k].monitoredTGs[nodes].toString() +" "
	  
	}

		var image= '<img src="images/talking.gif" alt="talk" id="talking" width="25px">';	
       if(data.nodes[k].isTalker == false)
	{
	  $('#Reflektortable').append('<tr><td>'+k+'</td>'+'<td>'+data.nodes[k].tg+'</td>'+'<td class="red_collor"><?php echo _("NO")?></td><td class="text-primary">'+text+'</td><td></td><td></td></tr>');
	  if(current_talker == k)
	  {
		totalSeconds=0;
	  }	

	}
	else
	{
         $('#Reflektortable').append('<tr><td>'+k+'</td>'+'<td>'+data.nodes[k].tg+'</td>'+'<td class="green_collor" ><?php echo _("YES")?>'+image+'</td><td class="text-primary">'+text+'</td><td><label id="minutes">00</label>:<label id="seconds">00</label></td><td></td></tr>');
           ++totalSeconds;
		var minutesLabel = document.getElementById("minutes");
       	var secondsLabel = document.getElementById("seconds");
		current_talker = k;
            secondsLabel.innerHTML = pad(totalSeconds%60);
            minutesLabel.innerHTML = pad(parseInt(totalSeconds/60));
 
	}
}



interval = setTimeout(call_svxrefelktor, 1000);   
  });

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

	get_audio_from_date(output )

	// Click handlers for jPlayerPlaylist method demo

<?php
$result = mysqli_query($link, "SELECT * FROM `repeater`");

// Numeric array

// Associative array
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

    echo " prosess_json('repeater-info-" . $row["Name"] . ".json');";
}
?>	

	
	

});

//]]>
function listen_live()
{
var myPlaylist = new jPlayerPlaylist({
		jPlayer: "#jquery_jplayer_N",
		cssSelectorAncestor: "#jp_container_N"
	}, [], {
		playlistOptions: {
			enableRemoveControls: true
		},
		swfPath: "../dist/jplayer",
		supplied: "webmv, ogv, m4v, oga, mp3",
		useStateClassSkin: false,
		autoBlur: true,
		smoothPlayBar: true,
		keyEnabled: true,
	    size: {width: "100%", height: "0px"}
	});

  myPlaylist.add({
    title:"Live",
    artist:"Repeater",
    oga:"<?php echo $livelink?>"  
  });
  myPlaylist.play();
}

function get_audio_from_date(date)
{
var myPlaylist = new jPlayerPlaylist({
		jPlayer: "#jquery_jplayer_N",
		cssSelectorAncestor: "#jp_container_N"
	}, [], {
		playlistOptions: {
			enableRemoveControls: true
		},
		swfPath: "./dist/jplayer",
		supplied: "webmv, ogv, m4v, oga, mp3",
		useStateClassSkin: false,
		autoBlur: true,
		smoothPlayBar: true,
		keyEnabled: true,
	    size: {width: "100%", height: "0px"}
	});


$.getJSON('recording.php?date='+date, function (data) {

	   
      for (i = 0; i < data.length; i++) {
    	var title ="";
    	  if(data[i].text != null)
    	  {
    		  title =  data[i].text;
    	  }
        
      myPlaylist.add({
        title:title,
        artist:"Svxlink",
        oga:data[i].file
       // poster: "http://www.rfwireless-world.com/images/VHF-UHF-repeater.jpg"
      });
        
        
      
   }
  
  
  
   });
myPlaylist.play();

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
        echo " 	multi_stat_change('repeater-info-" . $row["Name"] . ".json',$i);";
    }

    echo "}, " . (200 + i * 20) . ");";
    $i ++;
}
?>	




}

var tg_collors = new Array();
function generate_coulor()
{
    $.getJSON( "<?php echo $serveradress ?>", function( data ) {
    
    
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
	
    });



  


    
}
function load_languge(lang)
{
	$.post( "change_languge.php", { locate_lang: lang })
	  .done(function( data ) {
		  location.reload(); 
	  });
	
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


function login_form()
{
	var login = $('#login').val();
	var password = $('#password').val();
	
	$.post( "login.php", { login: login, password: password })
	  .done(function( data ) {
		  if(data == "true ")
			  alert( "<?php echo _("Login sucsess")?>!");
		  else
	    	alert( "<?php echo _("Wrong username or password")?>" );

		  

	  });
	
}


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

</head>
<body>

	<div id="wrapper">

		<!-- Sidebar -->
		<nav id="sidebar" class="sidbar_bg">
			<div id="sidebar-wrapper">
				<ul class="list-unstyled nav nav-tabs nav-justified">
					<li class="sidebar-brand nav-item"><a href="#">
							<h1 class="wite_font">Svx Portal</h1>
					</a></li>
					<li class="nav-item"><img class="imagepading" src="loggo.png"
						alt="logga" /></li>
				</ul>
				<ul class="nav flex-column nav-pills" role="tablist">
					<li class="nav-item"><a class="nav-link active" href="#Reflector"
						data-toggle="tab"><i class="fas fa-broadcast-tower"></i> <?php echo _("Reflector clients")?> </a></li>
					<li class="nav-item"><a class="nav-link " href="#listen"
						data-toggle="tab"><i class="fas fa-headphones-alt"></i> <?php echo _("Monitor")?> </a></li>
					<li class="nav-item"><a class="nav-link" href="#Echolink"
						data-toggle="tab"><i class="fas fa-terminal"></i> <?php echo _("System description")?></a></li>
					<li class="nav-item"><a class="nav-link" href="#Dictionary"
						data-toggle="tab"><i class="fas fa-book"></i> <?php echo _("Talkgroups")?></a></li>
					<li class="nav-item"><a class="nav-link" href="list_reciver.php"><i
							class="fas fa-broadcast-tower"></i> <?php echo _("List reciver")?></a></li>

					<li class="nav-item"><a class="nav-link" href="#Statistics" onclick="get_statistics()"
						data-toggle="tab"><i class="fas fa-chart-bar"></i>  <?php echo _("Statistics")?></a></li>
						

					<li class="nav-item"><a class="nav-link" href="#Recivers2"
						onclick="load_reflector()" data-toggle="tab"><i
							class="fas fa-broadcast-tower"></i>  <?php echo _("Receivers")?></a></li>
					<li class="nav-item"><a class="nav-link" href="#Log"
						data-toggle="tab"><i class="fas fa-terminal"></i> <?php echo _("Log")?></a></li>
					<li class="nav-item"><a class="nav-link" href="#Table_ctcss"
						data-toggle="tab"><i class="fas fa-terminal"></i> <?php echo _("CTSS map table")?></a></li>
						
						
						
						
					<li class="nav-item"><a class="nav-link" href="#map_repeater"
						onclick="setTimeout(function(){
		   map.updateSize();connect_reflector();
	   },300); "
						data-toggle="tab"><i class="fas fa-map-marked"></i> <?php echo _("Map")?></a></li>
<?php if($use_logein != null){?>
						<li class="nav-item"><a  class="nav-link" href="#LoginTab" data-toggle="tab"><i class="fas fa-lock"></i> <?php echo _("Login")?></a></li>
<?php }?>

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







	

<li style="margin-left: 10px">

    <img  onclick="load_languge('en_UK')" src="images/flags/gb.svg" width="30px" alt="GB">

    
    <img onclick="load_languge('sv_SE')" src="images/flags/se.svg" width="30px" alt="Se">


    <img onclick="load_languge('nb_NO')" src="images/flags/no.svg" width="30px" alt="NO">

</li>
				</ul>
<div class="sidebar-footer wite_font">
 <div class="row " style="margin-left:10px ">
			&copy; SA2BLV <?php echo date("Y"); ?>
</div>
	</div>
			</div>

			<!-- /#sidebar-wrapper -->
		</nav>



		<!-- Page Content -->
		<div id="page-content-wrapper">

			<div id="my-tab-content" class="tab-content">

				<div class="tab-pane active" id="Reflector">


					<table id="Reflektortable" class="table">
						<th><?php echo _("Callsign")?></th>
						<th><?php echo _("TG")?></th>
						<th><?php echo _("Ver")?></th>
						<th><?php echo _("TGs")?></th>

					</table>
				</div>




				<div class="tab-pane " id="listen">
					<div class="row">
						<div class="col-md-6">
							<h1><?php echo _("QSO Monitor")?></h1>
							<hr />
							
	<div class="card" style="width: 100%;">
 	 <div class="card-body">


				</div>

							
				
							<div id="jp_container_N" class="jp-video jp-video-270p"
								role="application" aria-label="media player" style="width: 100%">
								<div class="jp-video-play">
									<button class="jp-video-play-icon" role="button" tabindex="0">play</button>
								</div>
								<div class="jp-playlist">
									<ul>
										<!-- The method Playlist.displayPlaylist() uses this unordered list -->
										<li>&nbsp;</li>
									</ul>
								</div>


								<div class="jp-type-playlist">
									<div id="jquery_jplayer_N" class="jp-jplayer"></div>
									<div class="jp-gui">

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
							</div>
								
							</div>
						</div>
						<div class="col-md-6">
							<br />
							<button type="button" onclick="listen_live()"
								class="btn btn-outline-success my-2 my-sm-0"><?php echo _('Listen LIVE')?></button>
							<a href="#menu-toggle"
								class="btn btn-outline-success my-2 my-sm-0" id="menu-toggle"><?php echo _('Toggle menu')?></a>
							<hr />
							<h3><?php echo _("Select date to listen")?></h3>
							<?php if($use_logein != null){?>
								<p><?php echo _("to use player you must login")?> </p>
							<?php }?>
							<p>
								<input type="text" id="datepicker"
									value="<?php echo date("Y-m-d")?>"
									onchange="get_audio_from_date(this.value)">
							</p>
							<hr />
							<h5 class="card-title" id="Stationid"><?php echo _('Signal')?></h5>
   						     <p class="card-text"><span id="signalpressent">0</span>% <?php echo _("Signal value  from Reciver")?>.</p>
							<div class="card" style="width: 100%">
           						<div id="Reciverbars_player"></div>
							</div>
							<hr />
							<table class="table table-striped">
								<thead>
									<tr>
										<th><?php echo _("Name")?></th>
										<th><?php echo _("Openings")?></th>
										<th><?php echo _("Uptime")?></th>
										<th><?php echo _("Color")?></th>
									</tr>
								</thead>
								<tbody>
	   

	<?php
	
	$sql_node ="SELECT sum(Talktime), `Callsign` FROM RefletorNodeLOG WHERE `Type` = '1' AND `Active` ='0' group by  `Callsign`";
	
	 
	function secondsToDHMS($seconds) {
	    $s = (int)$seconds;
	    if($s >0)
	       return sprintf('%d:%02d:%02d:%02d', $s/86400, $s/3600%24, $s/60%60, $s%60);
	    else
	        return "0:00:00:00";
	    
	}
	
	$sqlref = $link->query($sql_node);
	$timesum_node =array();
	

	while($row = $sqlref->fetch_assoc()) {
	    $timesum_node[$row["Callsign"]] =$row["sum(Talktime)"];
	    
	}

	
	
	

$result = mysqli_query($link, "SELECT * FROM `RefletorStations` where Callsign != '' ");

// Numeric array

// Associative array
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    echo "<tr>";
    echo "<td>" . $row["Callsign"] . "</td>";
    echo "<td>" . utf8_encode($row["Location"]) . "</td>";
    echo "<td>" .secondsToDHMS($timesum_node[$row["Callsign"]]). "</td>";
    
    echo "<td>".'<div style="border:2px solid black; width: 25px; height :25px;  background-color:'.$row["Collor"].' ">'."</td>";
    
    echo "</tr>";
}

?>
	</tr>
								</tbody>
							</table>


						</div>



					</div>


				</div>
				<div class="tab-pane" id="Echolink">
<!-- 					<h1>Commands</h1> -->
  <div class="row">
			<?php
if($usefile != null)
{
    $myfile = fopen("echolink.txt", "r") or die("Unable to fla file!");
    echo utf8_encode(fread($myfile, filesize("echolink.txt")));
    fclose($myfile);
}else
{
    if($iframe_documentation_url != null )
     ini_set('default_socket_timeout', 20); // 900 Seconds = 15 Minutes
    $data = file_get_contents($iframe_documentation_url);
    echo $data;
?>

<?php }?>	
	</div>
				</div>
				<div class="tab-pane" id="Recivers2">
					<div class="container">
						<nav
							class="navbar navbar-expand-lg navbar-light bg-light navbar navbar-light bg-light justify-content-between">
							Filter:<select id="selects" class="w-25"
								onchange="update_filter(this.value)">
								<option value="">-- <?php echo _("All")?> --</option>
							</select> <a href="#menu-toggle" onclick="toogle_menu()"
								class="btn btn-outline-success my-2 my-sm-0" id="menu-toggle"><?php echo _("Toggle menu")?></a>
						</nav>
					</div>
					<div id="holder" class="container"></div>

				</div>

				<div class="tab-pane" id="Recivers">
					<a href="#menu-toggle" class="btn btn-outline-success my-2 my-sm-0"
						id="menu-toggle"><?php echo _("Toggle menu")?></a>
				</div>



				<div class="tab-pane" id="Recivers2"></div>
				<div class="tab-pane" id="Dictionary">
					<table class="table table-striped">
						<thead>
							<tr>
								<th><?php echo _("TG")?></th>
								<th><?php echo _("Name")?></th>
								<th><?php echo _("Last active")?></th>
								<th><?php echo _("Time")?></th>
								<th><?php echo _("Time Total")?></th>
								<th><?php echo _("Color")?></th>
							</tr>
						</thead>
						<tbody>
	   

	<?php
	$sql_active ="SELECT sum(Talktime), `Talkgroup` FROM RefletorNodeLOG WHERE `Type` = '1' AND `Active` ='0' group by  `Talkgroup`";
	
	$sql_nonactive ="SELECT sum(UNIX_TIMESTAMP(`Time`)), `Talkgroup` FROM RefletorNodeLOG WHERE `Type` = '1' AND `Active` ='0' group by  `Talkgroup` ";
	
	
	$sqlref = $link->query($sql_active);

	$timesum =array();
	
	while($row = $sqlref->fetch_assoc()) {
	    $timesum[$row["Talkgroup"]] =$row["sum(Talktime)"];
	}

	
mysqli_set_charset($link,"utf8");
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

    
    echo "<td><spawn id=\"Last_".$row["TG"]."\">" . $row1["Callsign"] . "</spawn></td>";
    echo "<td>".$row1["Time"]."</td>";
    echo "<td>".secondsToDHMS($timesum[ $row["TG"]])."</td>";
    echo "<td>".'<div style="border:2px solid black; width: 25px; height :25px;  background-color:'.$row["Collor"].' ">'."</td>";
    
    echo "</tr>";
}

?>
	</tr>
						</tbody>
					</table>
				</div>

				<div class="tab-pane " id="Log">

					
					<div id="logdiv1" class="col-xs-6">
					<?php include_once 'log.php';?>
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
									aria-haspopup="true" aria-expanded="false"> <?php echo _("Coverage")?> </a>
									<div class="dropdown-menu"
										aria-labelledby="navbarDropdownMenuLink">
										<a class="dropdown-item" onclick="show_covige()" href="#"><?php echo _("Show")?></a>
										<a class="dropdown-item" onclick="remove_covige()" href="#"><?php echo _("Remove")?></a>

									</div></li>
								<li class="nav-item dropdown"><a
									class="nav-link dropdown-toggle" href="#"
									id="navbarDropdownMenuLink" data-toggle="dropdown"
									aria-haspopup="true" aria-expanded="false"> <?php echo _("Actions")?> </a>
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
		  
		  <a class="dropdown-item" onclick="map.overlays_.clear();" href="#"><?php echo _("Remove bars")?> </a> <a class="dropdown-item"
											onclick="vectorSource.clear();Barsource.clear();map.overlays_.clear();"
											href="#"><?php echo _("Remove ALL stations")?></a> <a class="dropdown-item"
											onclick="prosess_json_reflecktor();" href="#"><?php echo _("Show Receivers")?></a>
											
											<a class="nav-link dropdown-toggle" href="#"
									onclick="toogle_AutoFollow()" id="Autofollow_text"
									data-toggle="dropdown" aria-haspopup="true"
									aria-expanded="false"> <?php echo _("Toggle AutoFollow")?> </a>
									
									
									<a class="nav-link dropdown-toggle" href="#"
									onclick="connect_reflector()" id="Autofollow_text"
									data-toggle="dropdown" aria-haspopup="true"
									aria-expanded="false"><?php echo _("Reload Map")?></a>
									
									<a class="nav-link dropdown-toggle" href="#"
									onclick="toogle_menu()" id="navbarDropdownMenuLink"
									data-toggle="dropdown" aria-haspopup="true"
									aria-expanded="false"> <?php echo _("Toggle menu")?> </a>

									</div></li>
						
			


							</ul>






						</nav>
						
					</div>
					</div>


					<div style="padding: 0 15px">
						<div class="row" style="">
							<div class="col col-md-8">
								<div class="map"
									style="width: 83.3%; height: 83.3%; position: fixed" id="map"></div>

							</div>

						</div>
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
										data-dismiss="modal">Close</button>

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
		if(currZoom <= 9)
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
		     var identity =station_identifire[idn.slice(4)];
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
				if(freq != null)
					freq = freq.toFixed(3);
				var enabled = data.nodes[identity].qth[qth].rx[rx].enabled
				if(enabled == undefined)
				{
					enabled= "true";
				}
				$('#optable_stn').append('<tr><td>'+name+'</td><td>'+sqlType+'</td><td>'+freq+'</td><td>'+enabled+'</td></tr>');

			}
		}
				

		
	});

	 
}

function update_tx_station(lat,lon,idn,tg,label,active)
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
    
    
    	
    	html=html.replace(" ","_");
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
    	if(active == false)
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

    	if(newZoom >= 7)
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



	setTimeout(function(){
		add_tx_station();
		// add recivers on start
		//prosess_json_reflecktor();



		setTimeout(function(){
			
			kill_loop=0;
			update_tx_station_loop()

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
            url: src,
            imageExtent: [c1[0], c1[1], c2[0], c2[1]]
        })
    });
    ov[src].setZIndex(parseInt(0, 10) || 0);   
    map.addLayer(ov[src]);
    return ov[src];
}

function prosess_json_reflecktor()
{

  $.getJSON("<?php echo $serveradress ?>", function(data){
  //console.log(data);
    
		for(var k in data.nodes){
			
		    if(data.nodes[k].hidden == true)
		    {
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
			        
    				var lat =data.nodes[k].qth[qth].pos.lat
    				var lon =data.nodes[k].qth[qth].pos.long;
    				var name = k+" "+data.nodes[k].qth[qth].rx[qth1].name;
    				var talkgroup =data.nodes[k].tg;
    				var idn=k+data.nodes[k].qth[qth].rx[qth1].name;
    				idn =idn.replace(" ","_");
    	
//   				console.log(tx_count);
    				if(tx_count>0)
    				{
        				if(count_rx >1)
        				{
            				
            				var group_idn= k+data.nodes[k].qth[qth].name;
            				group_idn =group_idn.replace(" ","_");
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
        					addtext(0,lat, lon,idn);
        					Draw_bar(idn, lat,lon);
        				}
    				}
    				else
    				{
        				if(count_rx >1)
        				{

        					var group_idn= k+data.nodes[k].qth[qth].name;
        					group_idn =group_idn.replace(" ","_");
							if(Barsource.getFeatureById(group_idn) == null)
							{
        						add_repeater_node(lat, lon,name,group_idn);
							}
        					
        				}
        				else
        				{
  //      					console.log("else rx: " +idn+" "+lat+" "+lon);
        					add_repeater_node(lat, lon,name,idn);
        				}
    					

    					
    				}
    
    				
    				//setmap(lat, lon,5);

		        }

			}	
		}
			
	
	
			

		
		
    
  });

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
	get_station_chat();
}
var show_all_tg =1;
var use_hour=0;
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



	
	$.get( "get_statistics.php", { date: date_value, time:"true"} )
	  .done(function( data ) {
		  var jsondata = JSON.parse(data); 
		  //console.log(jsondata);
		 


	    	
		  var i =0;
		  // fuling för time 0-24
		  var data_to_set = new Array();
		  var labels = new Array();
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
						text: '<?php echo _("Hour activity ")?>'+date_value
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
		    					label: talkgroup+ ' time',
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


function get_station_chat()
{

	
	$('#Graph_Cricle_holder').html("");
	$('#Graph_Cricle_holder').html('<canvas id="Graph_Cricle" width="400px" height="400px"></canvas>');

	
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
	$.get( "get_statistics.php", { date: date_value,qrv :1} )
	  .done(function( data ) {
		  //console.log("chart");
		  console.log(data);
		 
		  var Stations_json = JSON.parse(data); 

	
		  for(var station in Stations_json.data)
		  {
			  console.log(Stations_json.data[j]);
			  Stations_timesum[j]=0;
			  Stations_timesum[j] = Stations_json.data[j].time;
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
			  var preccent_network= (((Stations_json.data[j].time)/Stations_json.total_secounds) * 100).toFixed(3);
			  
			  $("#nodes_activity").append('<tr><td>'+Stations_json.data[j].call+'</td><td>'+Stations_json.data[j].Secound+"</td><td>"+preccent_network+"%</td><td>"+preccent+"%</td><td>"+Stations_json.data[j].reciver+"</td><tr>");
			  j++;

			 
		  }
		
	

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
function show_covige()
{
	if(Lock_show == 0)
	{
		Lock_show =1;

		<?php

				$result = mysqli_query($link, "SELECT * FROM `covrige` ");

				// Numeric array

				// Associative array
				while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		
				    echo "" . $row["Radiomobilestring"] . "";

		
				}

				?>

		

		
	}
}
function remove_covige()
{
	window.map.removeLayer(ov["riu.png"]);
	window.map.removeLayer(ov["gw.png"]);



			
	Lock_show  =0;
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
		}
		
		for(var k in data.nodes){
		    for(var qth in data.nodes[k].qth){
		    	//console.log(k+" - "+data.nodes[k].qth[qth].name);
		        for(var qth1 in data.nodes[k].qth[qth].tx){
			        		        
                    var lat =parseFloat(data.nodes[k].qth[qth].pos.lat);

                    var lon =parseFloat(data.nodes[k].qth[qth].pos.long);
                    
    				var name = k+" "+data.nodes[k].qth[qth].tx[qth1].name;
    				var talkgroup =data.nodes[k].tg;
    				var idn=k+data.nodes[k].qth[qth].tx[qth1].name;
    				idn =idn.replace(" ","_");
    				station_identifire[idn] =k;
    				//console.log(name);
    				
    
    				add_repeater_transmiter(lat,lon,name,idn,talkgroup)
    		
    				// tempoary fix if useing mor than 1 transmitter on same qth
    				setmap(lat, lon,8);
    				break;
		        	
		        }

			}	
		}

		//add_repeater_node()

		<?php if($default_long != "" && $default_lat !=""){?>
			setmap(<?php echo $default_lat?>, <?php echo $default_long?>,<?php echo $default_zoom?>);
		<?php }?>
		});
	
}
var kill_loop =0;
function update_tx_station_loop()
{
	$.getJSON( "<?php echo $serveradress ?>", function( data ) {
		for(var k in data.nodes){
			
		    if(data.nodes[k].hidden == true)
		    {
		    	delete data.nodes[k];
		    	
		    }
		}
		
		for(var k in data.nodes){


			
		    for(var qth in data.nodes[k].qth){
		        for(var qth1 in data.nodes[k].qth[qth].tx){
				var lat =data.nodes[k].qth[qth].pos.lat
				var lon =data.nodes[k].qth[qth].pos.long;

				var talkgroup =data.nodes[k].tg;
				
				var name = k+" "+data.nodes[k].qth[qth].tx[qth1].name;
				var idn=k+data.nodes[k].qth[qth].tx[qth1].name;
				var active =data.nodes[k].qth[qth].tx[qth1].transmit;
				if(active == null)
				{
					active = false;
				}
				idn =idn.replace(" ","_");
				update_tx_station(lat,lon,idn,talkgroup,name,active);
		        	

		        }
				var count_rx =0;
				for(var q in data.nodes[k].qth[qth].rx)
			    {
		        	count_rx++;
			    }	
        

		        for(var qth1 in data.nodes[k].qth[qth].rx)
		        {
		        
        			var lat =data.nodes[k].qth[qth].pos.lat
        			var lon =data.nodes[k].qth[qth].pos.long;
        			var name = k+" "+data.nodes[k].qth[qth].rx[qth1].name;
        			var talkgroup =data.nodes[k].tg;
        			var idn=k+data.nodes[k].qth[qth].rx[qth1].name;
        			idn =idn.replace(" ","_");
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
        				var group_idn = k+data.nodes[k].qth[qth].name;
        				group_idn =group_idn.replace(" ","_");
        				if(Barsource.getFeatureById(group_idn) != null)
        				{
                			update_text_byid(group_idn,value.toString(),sql);
                			update_bar(group_idn,value.toString(),sql);
            			}
            			break;
            	
        			}
        			else if(count_rx > 1)
        			{
        				var group_idn = k+data.nodes[k].qth[qth].name;
        				group_idn =group_idn.replace(" ","_");
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



		});
	if(kill_loop == 0)
	instervalls = setTimeout(update_tx_station_loop, 500);
}


</script>


					</div>


				</div>






				<!-- Menu Toggle Script -->
				<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
	
function toogle_menu()
{
        $("#wrapper").toggleClass("toggled");	
}

function change_day_next()
{

    var date = $('#Datepicker_graph').datepicker('getDate');
    date.setDate(date.getDate() +1)
    $('#Datepicker_graph').datepicker('setDate', date);
    get_statistics();
    get_statistics_hour();

}
function change_day_prew()
{

	var date = $('#Datepicker_graph').datepicker('getDate');
	date.setDate(date.getDate() -1)
	$('#Datepicker_graph').datepicker('setDate', date);
    get_statistics();
    get_statistics_hour();


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





   
	
    </script>

				<div class="tab-pane" id="LoginTab">
					<div class="wrapper ">
						<div id="formContent">
							<!-- Tabs Titles -->

							<!-- Icon -->
							<div class="fadeIn first">
								<img src="images/locked.svg" alt="Kiwi standing on oval"
									width="100px">
							</div>

							<!-- Login Form -->
							<form id="loginform" action="login.php" method="post" >
								<input type="text" id="login" class="fadeIn second" name="login"
									placeholder="<?php echo _("Username")?>"> <input type="password" id="password"
									class="fadeIn third" name="password" placeholder="<?php echo _("password")?>"> <input onclick="login_form()"
									type="button" class="fadeIn fourth" value="<?php echo _("Log In")?>">
							</form>

							<!-- Remind Passowrd -->
							<div id="formFooter">
								<a class="underlineHover" href="#"></a>
							</div>

						</div>
					</div>
				</div>


				<div class="tab-pane " id="Statistics">
				<div class="row">
    				<h3><?php echo _("Statistics for Network")?></h3>
    			</div>
    			<div class="row">
    			
    				<p><button class="prev-day btn btn-outline-secondary"  onclick="change_day_prew()" id="prev-day"><i class="fa fa-angle-left" aria-hidden='true'></i></button></button><input type="text" id="Datepicker_graph" value="<?php echo date("Y-m-d")?>" onchange="get_statistics();get_statistics_hour()">
    				
    				<button class='next-day btn btn-outline-secondary' onclick="change_day_next()" ><i class='fa fa-angle-right' aria-hidden='true'></i></button></p>
				</div>
        	  <div class="col">
               <nav id="ssas" class="navbar navbar-expand-lg navbar-light bg-light navbar navbar-light bg-light ">
        
                   <ul class="navbar-nav">
                  	<li class="nav-link  active"><a data-toggle="tab" onclick="$('#ssas a.active').removeClass('active');" href="#dastaty"><?php echo _("Day")?></a></li>
                  	<li class="nav-link "><a data-toggle="tab" onclick="$('#ssas a.active').removeClass('active');get_statistics_hour()" href="#menu_hour"><?php echo _("Hour")?></a></li>

                   </ul> 
            </nav>
            </div>
        
             
        

        		<div class="row">
				<div class="tab-content col-md-12">
				
				
     			<div id="dastaty" class="tab-pane in active w-100  ">
                    <div class="container-fluid">
                        <div class="row">
                        	<div class="col-md-6">
                				<div style="width: 80%; height: 500px;" id="canvas_grap_holder">
                					<canvas id="Graph" width="400px" height="400px"></canvas>
                				</div>
                			</div>
                    		<div class="col-md-6" id="Graph_Cricle_holder">
                    				<canvas id="Graph_Cricle" width="400px" height="400px"></canvas>
                    		</div>	
                    	 </div>
                	 </div>
            	 </div>
            	 
      			<div id="menu_hour" class="tab-pane  in  w-100  ">
                    <div class="container-fluid">
                        <div class="row">
                        	<div class="col-md-12">
                				<div style="width: 80%; height: 500px;" id="canvas_grap_holder1">
                					<canvas id="Graph1" width="400px" height="400px"></canvas>
                				</div>
                			</div>

                    	 </div>
                	 </div>
            	 </div>
            	 
            	            	 
            	 </div>
        			
    			</div>


    			<div class="row">
    				<div class="col-md-12">
    					&nbsp;
    				</div>
    			</div>
    			<div class="col-md-12" id="table">
    			<table id="nodes_activity" class="table" ><thead class="thead-dark"><tr><th><?php echo _("Station")?></th><th><?php echo _("Uptime")?></th><th><?php echo _("Network Usage 24 hour")?></th><th><?php echo _("Usage last 24 hour")?></th><th><?php echo _("Most used reciver")?></th></tr></thead></table>
    			</div>
    			</div>	
    			
    			<div class="tab-pane " id="Table_ctcss">
    			<?php 
    			$noheader =1;
    			include 'ctcss_map_table.php'?>
    			</div>
				
			</div>
			<!-- /#page-content-wrapper -->

		</div>
		<!-- /#wrapper -->

</body>

</html>
