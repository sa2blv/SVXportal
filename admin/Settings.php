<?php
include_once "../config.php";
include_once '../function.php';
function Type_one($parameter,$value,$txt) {
    
?>
<div class="row">
	<div class="col-sm-5">
		<p><?php echo _($txt)?></p>
	
	</div>
	
	<div class="col-sm-7">
  
  
        <div class="form-check form-check-inline" >
        <input class="form-check-input" type="radio" name="<?php echo $parameter?>" id="<?php echo $parameter?>2" value="0" <?php if($value == 0){ echo "checked";}?>>
        <label class="form-check-label" for="<?php echo $parameter?>2">
        <?php echo _('No');?>
        </label>
        </div>
        <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="<?php echo $parameter?>" id="<?php echo $parameter?>1" value="1" <?php if($value == 1){ echo "checked";}?>>
        <label class="form-check-label" for="<?php echo $parameter?>1">
        <?php echo _('Yes');?>
        </label>
        </div>
    
    </div>
</div>



<?php 
    
    
}
function Type_two($parameter,$value,$txt) {
    
?>
  <div class="form-group row">
    <label for="<?php echo $parameter?>_1" class="col-sm-5 col-form-label"><?php echo _($txt)?></label>
    <div class="col-sm-7">
      <input type="text" class="form-control" name="<?php echo $parameter?>" id="<?php echo $parameter?>_1" value="<?php echo $value?>">
    </div>
  </div>


<?php 

    
}
function Type_Zero($param) {
    
    
    
}

if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 ){

?>
<script type="text/javascript">
function settings_form_sumbit()
{
	$.post( "admin/update_setings.php", $( "#Setting_from" ).serialize() )
	.done(function( data ) {
		alert("<?php echo _('Settings is saved!')?>")

	});
	

	return false;
}

</script>
<h1><?php echo _('Settings')?></h1>

<form action="#" id="Setting_from" onsubmit="return  settings_form_sumbit()">

<?php 


$result = mysqli_query($link, "SELECT * FROM `Settings`  ");



while($row = $result->fetch_assoc()) {

    switch($row['type'])
    {
        case 0:
            break;
        case 1:
            Type_one($row['Define'],$row['value'],$row['Name']);
            break;
        case 2:
            Type_two($row['Define'],$row['value'],$row['Name']);
            break;
            
            
        Default:    
            break;
                
    }
    
    
}



?>

<button type="submit" class="btn btn-primary"><?php echo _('Save')?></button>

</form>

<?php }?>