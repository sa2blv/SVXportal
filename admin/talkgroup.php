<?php
if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 ){
?>
<script type="text/javascript">

function update_color_tg(id,color) {
	$('#color_id1').val(id);
	$('#colo1r').val(color.trim());
	$("#Collor_tg").modal() 
}
function update_color_tg_submit(){


	$.post( "admin/update_color.php", $( "#Collor_form_tg" ).serialize() )
	.done(function( data ) {
		reaload_tg_table();
	});
	
	return false;

}
function Delete_tg(id){


	if( confirm("<?php echo _('Please confirm user removal')?>") == true )
    	$.post( "admin/update_color.php",  { tgdel: "1", id: id } )
    	.done(function( data ) {
    		reaload_tg_table();
    
    	});
	
	

	return false;

}

function create_tg()
{
	var usern = $('#tgid').val();
	var pass = $('#Description').val();

	if(usern != "" && pass != "")
	{
    	$.post( "admin/update_color.php", $( "#create_talkgroup" ).serialize() )
    	.done(function( data ) {
    		alert("<?php echo _('talkgroup created!')?>")
    		reaload_tg_table();
    		$('#create_talkgroup').trigger("reset");

    		
    
    	});
	}
	

	return false;
}
function reaload_tg_table()
{
	$.get( "admin/talkgroup_get.php", function( data ) {


		  $("#talkgourp_print tbody").html(data);
		});
}




</script>

  <nav class="navbar navbar-expand-lg navbar-light  bg-light" style="background-color: #e3f2fd;">
		
		<a class="navbar-brand" href="#"><?php echo _('Talkgroup')?></a>
		
  <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">


      
             <li class="nav-item">
        
             
        <a class="nav-link " href="#" id="navbarDropdownMenuLink" onclick="PrintElem('talkgourp_print_d','<?php echo _('Log print')?>')" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <i class="fas fa-print"></i>
          <?php echo _('Print')?>
        </a>
             
        </li>
         <li class="nav-item">
                <a class="nav-link " href="#" id="navbarDropdownMenuLink" onclick="fnExcelexport('talkgourp_print')" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <i class="far fa-file-excel"></i>
          <?php echo _('Export xls')?>
        </a>
        </li>
        
    </ul>




      </div>

      
    </nav>


<form id="create_talkgroup"  onsubmit="return create_tg()">
    <h2><?php echo _('Create talkgroup')?>:</h2>
      <fieldset class="form-group">
  <div class="form-group">
  	<div class="col-sm-4">
   	 	<label for="tgid"><?php echo _('TG#')?></label>
   	</div>
   <div class="col-sm-8">
    	<input type="number" name="tgid" class="form-control" id="tgid" placeholder="">
   </div>
  </div>
  <div class="form-group">
  	<div class="col-sm-4">
    	<label for="Description"><?php echo _('Description')?></label>
    </div>
    <div class="col-sm-8">
    	<input type="text" name="Description" class="form-control" id="Description" placeholder="">
    </div>
  </div>
  
    <div class="form-group">
  	<div class="col-sm-4">
    	<label for="Color2"><?php echo _('Color')?></label>
    </div>
    <div class="col-sm-8">
    	<input type="color" name="Color" class="form-control" id="Color2" placeholder="">
    </div>
  </div>
  
  
  
    <div class="form-group">


      <div class="col-sm-4">
      	 <button type="submit" class="btn btn-primary"><?php echo _('Create talkgroup')?></button>
   
    </div>
    
    </div>
    </fieldset>
        	<input type="hidden" name="newtg" value="1" >
    
</form>

<div id="talkgourp_print_d">
    
    <table class="table table-sm" id="talkgourp_print">
    
      <thead class="thead-dark">
        <tr>

          <th scope="col"><?php echo _('TG#')?></th>
          <th scope="col"><?php echo _('Description')?></th>
          <th scope="col"><?php echo _('Restricted')?></th>
          <th scope="col"><?php echo _('Actions')?></th>
          <th scope="col"><?php echo _('Color')?></th>
        </tr>
      </thead>
      
      <tbody>
      
      <?php 
      
      
      $result = mysqli_query($link, "SELECT * FROM `Talkgroup` ");
      
      
      
      while($row = $result->fetch_assoc()) {
          
      ?>
      
        <tr>

      <td><?php echo $row['TG']?></td>
      <td><?php echo $row['TXT']?></td>
      <?php 
      if($reflektor_db == 1){
      ?>
     	 <td ><a class="btn btn-primary btn-sm" href="#" onclick="tg_acl_admin('<?php echo $row['TG'] ?>')" role="button"><?php echo  _('Reflector ACL')?></a></td>
      <?php }else{?>
      	<td ><button type="button" class="btn btn-secondary  btn-sm" disabled><?php echo  _('Reflector ACL')?></button></td>
      
     
      <?php }?>
      
 

            <td><i class="fas fa-trash" onclick="Delete_tg(<?php echo $row['ID']?>)"></i></td>
      
      <?php     echo "<td>".'<div onclick="update_color_tg('. $row['ID'].',\''.$row["Collor"].'\')" style="border:2px solid black; width: 25px; height :25px;  background-color:'.$row["Collor"].' ">'."</td>"; ?>
      </tr>
      <?php }?>
      </tbody>
      </table>
      
</div>      
   <div id="Collor_tg" class="modal fade" role="dialog">
  <div class="modal-dialog">
 <form id="Collor_form_tg" onsubmit="return update_color_tg_submit()">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title"><?php echo _('Change station color') ?></h4>
      </div>
      
      <div class="modal-body">
      
      
          <label for="color" class="sr-only"><?php echo _('Color') ?></label>
          
    		<input type="color" name="color" class="form-control" id="colo1r">
    		<input type="hidden" name="color_id" id="color_id1"> 
    		<input type="hidden" name="color_change_tg"  value="1"> 
      
     
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Close')?></button>
        <button type="submit" class="btn btn-default"><?php echo _('Update')?></button>
      </div>
       </form>
    </div>

  </div>
</div>

<script type="text/javascript">

function tg_acl_admin(tg)
{


	window.open ("admin/reflektor_acl.php?TG="+tg,
	"mywindow","menubar=1,resizable=1,width=800,height=800");

	
}


</script>
      
      
      
<?php }?>