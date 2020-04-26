<?php

include "config.php";
?> 
	<div class=recivers>
<?php 
if($_GET['id'] == "Menu" || $_GET['id'] == '')
{
	$result=mysqli_query($link,"SELECT * FROM `repeater`");

	// Numeric array

	// Associative array
	  while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC))
	{
		echo " <button class=\"btn btn-outline-success my-2 my-sm-0\" onclick=\"stat_change('repeater-info-".$row["Name"].".json')\" class=\"btn btn-default\" type=\"button\">".$row["Name"]."</button> ";
	}
	echo '<a href="#menu-toggle" onclick="Show_all_Recivers()"   class="btn btn-outline-success my-2 my-sm-0" id="menu-toggle1">Show all repeaters</a>';
	echo '<a href="#menu-toggle" onclick="toogle_menu()"   class="btn btn-outline-success my-2 my-sm-0" id="menu-toggle1">Toggle Menu</a>';
}
?>

<?php if($_GET['id'] != "Menu" ){
?>
	<div id = "box_2">
	<p><div id="box_1" class="head">Signal values at <span id="callsign<?php echo $_GET['id']?>">repeater</span>
	<span id="freq<?php echo $_GET['id']?>"></span><span style="float:right;"><span id="tx<?php echo $_GET['id']?>"><img src="/icons/ball.red.png"></span><span id="stream<?php echo $_GET['id']?>"></span></span></div>
	<p>
	<table>
	<tr><th>Receiver</th><th>Sql</th><th>Signal</th><th>Bargraph</th></tr>
	<tr id="sigtab<?php echo $_GET['id']?>"><td colspan="4">This page requires Javascript and a modern browser.</td></tr>
	</table>
		</div>
		</div>
<?php }?>