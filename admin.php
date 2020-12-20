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

<script type="text/javascript">

var loop_livelog =0;
var current_offset = 0
var log_size = 500;

function offset_log(offset) {
	loop_livelog=0;
	console.log(offset);
	var serch_string = $("#logserch").val();
	current_offset= offset;
	$.get( "admin/log.php", { offset: offset,search: serch_string,size: log_size }, function( data ) {
		  $( "#logdiv1" ).html( data );

		});
	return false;
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
    		$.get( "admin/log.php", { offset: current_offset,search: serch_string,only_table:1,size: log_size }, function( data ) {
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

</head>

<body>

<?php if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 ){?>

   <header>
 <nav class="navbar   navbar-dark sidebar_collor">
 <div>
   <a class="navbar-brand" href="#">
    <img src="loggo.png" alt="Logo" style="width:40px;">

  </a>

   <a class="navbar-brand wite_font" href="#">
   
     SVX Portal <?php echo _('admin interface')?>
   </a>
   
   </div>

  <div class="topnav-right" >
  <a href="index.php" onclick="" class="btn btn-outline-success my-2 my-sm-0" id="menu-toggle"><?php echo _('Back')?></a>
  </div>
</nav> 
    </header>

 <div class="container-fluid">

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo _('Stations')?></a>
  </li>
    <li class="nav-item">
    <a class="nav-link " id="Talkgroup-tab" data-toggle="tab" href="#Talkgroup" role="tab" aria-controls="Talkgroup" aria-selected="true"><?php echo _('Talkgroup')?></a>
  </li>
  
   <li class="nav-item">
    <a class="nav-link" id="Log-tab" data-toggle="tab" href="#Log" role="tab" aria-controls="log" aria-selected="false"><?php echo _('Fault log')?></a>
  </li>
  
  <li class="nav-item">
    <a class="nav-link" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="Users" aria-selected="false"><?php echo _('Users')?></a>
  </li>
  <?php if($reflektor_db == 1){?>
<li class="nav-item">
    <a class="nav-link" id="ReflektorUsers-tab" data-toggle="tab" href="#ReflektorUsers" role="tab" aria-controls="ReflektorUsers" aria-selected="false"><?php echo _('Reflektor users')?></a>
  </li>
  
  <?php }?>
  
	<li class="nav-item">
    <a class="nav-link" id="settings-tab" data-toggle="tab" href="#Settings" role="tab" aria-controls="Settings" aria-selected="false"><?php echo _('Settings')?></a>
  </li>
</ul>



<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
    <?php include "admin/stations.php";?>
  </div>
  <div class="tab-pane fade" id="Settings" role="tabpanel" aria-labelledby="Settings-tab">
  
  <?php include "admin/Settings.php";?>
  
  </div>
  <div class="tab-pane fade" id="Talkgroup" role="tabpanel" aria-labelledby="Talkgroup-tab">
  
  <?php include "admin/talkgroup.php";?>
  
  </div>  
    <?php if($reflektor_db == 1 && isset($reflektor_db)){?>
    
    <div class="tab-pane fade" id="ReflektorUsers" role="tabpanel" aria-labelledby="ReflektorUsers-tab">
  
  <?php include "admin/Reflektor_users.php";?>
  
  </div>  
   <?php }?>
  
  
<div class="tab-pane fade" id="Log" role="tabpanel" aria-labelledby="Log-tab">
  
					<div id="logdiv1" class="col-xs-6">
					<?php include_once 'admin/log.php';?>
					</div>
  
  </div>
  
  <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab">
  
    <?php include "admin/users.php";?>
  </div>
</div>










</div>
<?php }else{
    
    include 'admin/login.php';
    
}?>

</body>
</html>
