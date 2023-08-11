<?php

$svxlink_versonfile =  file_get_contents("https://raw.githubusercontent.com/sm0svx/svxlink/master/src/versions", 0, stream_context_create(["http"=>["timeout"=>1]]));
$svxlink_verson_array =parse_ini_string($svxlink_versonfile);

function station_class($station,$latest_version)
{
    $station = trim($station);
    $latest_version = trim($latest_version);
    
    if($station == $latest_version && $station != "")
    {
        return "table-success";
    }
    elseif($station != $latest_version && $station != "")
    {
        
        return "table-warning";
    }
    else
    {
        return "table-info";
        
    }
    return "";
}

?>
 <script> 
 function update_color(id,color)
  {
	$('#color_id').val(id);
	$('#color').val(color.trim());
	$("#Collor").modal() 
}
var current_mqtt="";

function mqtt_page(node) 
 {

	    $("#station_mqtt").html(node);
		$("#mqtt").modal() 
		
		current_mqtt = node;
		


		$.get( "mqtt_admin/get_usertable.php", { node: node  } )
		  .done(function( data ) {
		    $("#box_data").html(data);
		  });

		


		
}


function  create_mqtt_user()
{

	var randomstring = Math.random().toString(36).slice(-8);
	
	let pass = prompt("<?php echo _('Enter passsord')?>",randomstring);

	if (pass != null) 
	{

		$.post( "mqtt_admin/create_user_mqtt.php?action=Create", { password: pass, node: current_mqtt })
		  .done(function( data ) {
			  mqtt_page(current_mqtt);
		  });


		  

	} 

	
}
function  restor_mqtt_user()
{

	$.post( "mqtt_admin/create_user_mqtt.php?action=Restore_acl", {  node: current_mqtt })
	  .done(function( data ) {
		  mqtt_page(current_mqtt);
	  });


	
}




function  create_mqtt_Topic()
{

	
	let pass = prompt("<?php echo _('Enter Topic')?>","");

	if (pass != null) 
	{

		var answer = window.confirm("<?php echo _('Read write ?')?>");
		var val =0;
		if (answer) {
			val =1;
		    //some code
		}
		else {
		    //some code
		}


		

		$.post( "mqtt_admin/create_user_mqtt.php?action=Add_topic", { topic: pass, node: current_mqtt , rw : val })
		  .done(function( data ) {
			  mqtt_page(current_mqtt);
		  });


		  

	} 

	
}


function Delete_topic_mqtt(id)
{
	
	$.post( "mqtt_admin/create_user_mqtt.php?action=remove_topic", { id : id, node: current_mqtt  })
	  .done(function( data ) {
		  mqtt_page(current_mqtt);
	  });
	
}







function update_mqtt_user()
{

	var randomstring = Math.random().toString(36).slice(-8);
	
	let pass = prompt("<?php echo _('Enter passsord')?>",randomstring);

	if (pass != null) 
	{

		$.post( "mqtt_admin/create_user_mqtt.php?action=Restore_Password", { password: pass, node: current_mqtt })
		  .done(function( data ) {
			  mqtt_page(current_mqtt);
		  });


		  

	} 

	
}



 
function update_color_set()
{


	$.post( "admin/update_color.php", $( "#Collor_form" ).serialize() )
	.done(function( data ) {
		reaload_user_stations();
	});
	
	return false;

}




function reaload_user_stations()
{
	$.get( "admin/get_station.php", function( data ) {


		  $("#station_table_date tbody").html(data);
		});
}



$(document).ready(function(){
	  $("#Stationserach").on("keyup", function() {
	    var value = $(this).val().toLowerCase();
	    $("#station_table_date tbody tr").filter(function() {
	      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
	    });
	  });

	  $('th').click(function(){
		    var table = $(this).parents('table').eq(0)
		    
		    var rows = table.find('tbody tr').toArray().sort(comparer($(this).index()))
		    console.log(rows);
		    this.asc = !this.asc
		    if (!this.asc){rows = rows.reverse()}
		    for (var i = 0; i < rows.length; i++){table.append(rows[i])}
		})

		
	});


function comparer(index) {
    return function(a, b) {
        var valA = getCellValue(a, index), valB = getCellValue(b, index)
        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
    }
}
function getCellValue(row, index){ return $(row).children('td').eq(index).text() }
	
	


 </script> 
 
 <style>
<!--
th{
cursor: pointer;

}
-->
</style> 
 
   <nav class="navbar navbar-expand-lg navbar-light  bg-light" style="background-color: #e3f2fd;">
		
		<a class="navbar-brand" href="#"><?php echo _('Stations')?></a>
		
  <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">


      
             <li class="nav-item">
        
             
        <a class="nav-link " href="#" id="navbarDropdownMenuLink" onclick="PrintElem('station_data>','<?php echo _('Stations')?>')" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <i class="fas fa-print"></i>
          <?php echo _('Print')?>
        </a>
             
        </li>
         <li class="nav-item">
                <a class="nav-link " href="#" id="navbarDropdownMenuLink" onclick="fnExcelexport('station_table_date')" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <i class="far fa-file-excel"></i>
          <?php echo _('Export xls')?>
        </a>
        </li>
             <li class="nav-item">
                <a class="nav-link " href="#" id="navbarDropdownMenuLink" onclick="reaload_user_stations()" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-sync-alt"></i>
          <?php echo _('Update')?>
        </a>
        </li>
        
    </ul>
    
    <div>
    <input class="form-control mr-sm-2" id="Stationserach" type="search" placeholder="<?php echo _('Search')?>" value="" aria-label="Search">
    
    
    </div>







      </div>

      
    </nav>
 
 <div id="station_data>">

    <table class="table table-sm table-striped table-hover" id="station_table_date">
      <thead class="thead-dark">
        <tr>
          <th scope="col"><?php echo _('Id')?></th>
          <th scope="col"><?php echo _('Callsign')?></th>
          <th scope="col"><?php echo _('Location')?></th>
          <th scope="col"><?php echo _('Version')?></th>
          <th scope="col"><?php echo _('Sysop')?></th>
          <th scope="col"><?php echo _('Last seen')?></th>
          <th scope="col"><?php echo _('Station down')?></th>
          <th scope="col"><?php echo _('Other')?></th>
          <th scope="col"><?php echo _('Color')?></th>
        </tr>
      </thead>
      <tbody>
      
      <?php 
      
      
      $result = mysqli_query($link, "SELECT * FROM `RefletorStations` where Callsign != '' ORDER BY Callsign ");
      
      
      $count_up =0;
      $count_down =0;
      
      while($row = $result->fetch_assoc()) 
      {
          
          if($row['Station_Down'] == 1)
          {
              $class ="table-danger";
              $count_down++;
          }
          else
          {
              $class ="table-success";
              $count_up++;
              
          }
          if($row['Monitor'] == 0)
          {
              $class ="table-info";
          }
          
              
          
      ?>
      
      <tr>
          <td style="text-transform: uppercase;"><b><?php echo $row['ID']?></b></td>
          <td style="text-transform: uppercase;"><?php echo $row['Callsign']?></td>
          <td><?php echo $row['Location']?></td>
          <td class="<?php echo station_class($row['Version'],$svxlink_verson_array['SVXLINK'])?> !important"><?php echo $row['Version'] ?></td>
        
          
          <td style="text-transform: uppercase;"><b><?php echo $row['Sysop']?></b></td>
            <td><?php echo $row['Last_Seen']?></td>
          
        <?php   if($row['Station_Down'] == 1){?>
          <td class="<?php echo  $class?>"><?php echo _('Yes')?></td>
        <?php }else{?>
          <td class="<?php echo  $class?>"><?php echo _('No')?></td>
        <?php }?>
        
        <?php if($use_mqtt == True){?>
        <td><button onclick="mqtt_page('<?php echo $row['Callsign']?>')" type="button" class="btn btn-primary btn-sm"><?php echo _('Mqtt')?></button></td>
        <?php }else{?>
        <td></td>
        <?php } ?>
        
        
        <?php     echo "<td class=\"\" >".'<div onclick="update_color('. $row['ID'].',\''.$row["Collor"].'\')" style="border:2px solid black; width: 25px; height :25px;  background-color:'.$row["Collor"].' ">'."</td>"; ?>
     
     
     </tr>
      
      
      <?php }?>
  

    

  </tbody>
  <tfoot class="table-dark">
  <tr><td></td><td></td><td><?php echo _('Latest version')?></td><td><?php echo $svxlink_verson_array['SVXLINK']?></td><td colspan ="2"><?php echo _('Total up / down')?></td><td><?php echo $count_up ." / ". $count_down?></td><td><?php echo _('Total') ." ".($count_up+ $count_down) ?></td><td></td></tr>
  </tfoot>
</table>




  <div id="mqtt" class="modal fade" role="dialog">
  <div class = "modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title"><?php echo _('MQTT admin') ?> &nbsp;<span id="station_mqtt"></span></h4>
      </div>
      
      <div class="modal-body">
      <div id="box_data">
      
      
      
      </div>

      

        
      </div>
      <div class="modal-footer">
        <button type="button" onclick="create_mqtt_Topic()" class="btn btn-default btn-primary" ><?php echo _('Create topic')?></button>
        <button type="button" onclick="update_mqtt_user()" class="btn btn-default btn-primary " ><?php echo _('Change password')?></button>
        <button type="button"  onclick="create_mqtt_user()"  class="btn btn-default btn-primary" ><?php echo _('Create user')?></button>
        <button type="button"  onclick="restor_mqtt_user()"  class="btn btn-default btn-primary" ><?php echo _('Restore user')?></button>
        
        
        <button type="button" class="btn btn-default btn-danger" data-dismiss="modal"><?php echo _('Close')?></button>
      </div>

    </div>

  </div>
</div>
  
  
  
<div id="Collor" class="modal fade" role="dialog">
  <div class="modal-dialog">
 <form id="Collor_form" onsubmit="return update_color_set()">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title"><?php echo _('Change station color') ?></h4>
      </div>
      
      <div class="modal-body">
      
      
          <label for="color" class="sr-only"><?php echo _('Color') ?></label>
          
    		<input type="color" name="color" class="form-control" id="color">
    		<input type="hidden" name="color_id" id="color_id"> 
    		<input type="hidden" name="color_change_station"  value="1"> 
      
      
        
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Close')?></button>
        <button type="submit" class="btn btn-default"><?php echo _('Update')?></button>
      </div>
       </form>
    </div>

  </div>
  
  
  
  
  


</div>


  
  



  
  

  
  
  
  
</div>