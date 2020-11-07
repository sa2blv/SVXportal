<?php


?>
 <script> 
 function update_color(id,color) {
	$('#color_id').val(id);
	$('#color').val(color.trim());
	$("#Collor").modal() 
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




 </script> 
 
 
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







      </div>

      
    </nav>
 
 <div id="station_data>">

    <table class="table" id="station_table_date">
      <thead class="thead-dark">
        <tr>
          <th scope="col"><?php echo _('Callsign')?></th>
          <th scope="col"><?php echo _('Location')?></th>
          <th scope="col"><?php echo _('Last seen')?></th>
          <th scope="col"><?php echo _('Station down')?></th>
          <th scope="col"><?php echo _('Color')?></th>
        </tr>
      </thead>
      <tbody>
      
      <?php 
      
      
      $result = mysqli_query($link, "SELECT * FROM `RefletorStations` where Callsign != '' ");
      
      
      
      while($row = $result->fetch_assoc()) {
          
          if($row['Station_Down'] == 1)
          {
              $class ="table-danger";
          }
          else
          {
              $class ="table-success";
          }
              
          
      ?>
      
      <tr class="<?php echo  $class?>">
      <td><?php echo $row['Callsign']?></td>
      <td><?php echo $row['Location']?></td>
      <td><?php echo $row['Last_Seen']?></td>
    <?php   if($row['Station_Down'] == 1){?>
      <td><?php echo _('Yes')?></td>
    <?php }else{?>
      <td><?php echo _('No')?></td>
    <?php }?>
    
    <?php     echo "<td>".'<div onclick="update_color('. $row['ID'].',\''.$row["Collor"].'\')" style="border:2px solid black; width: 25px; height :25px;  background-color:'.$row["Collor"].' ">'."</td>"; ?>
      </tr>
      
      
      <?php }?>
  

    

  </tbody>
</table>
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