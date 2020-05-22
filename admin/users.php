<?php
if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 ){

    
?>
<script type="text/javascript">
function create_user()
{
	var usern = $('#usern1').val();
	var pass = $('#password1').val();

	if(usern != "" && pass != "")
	{
    	$.post( "admin/user_action.php", $( "#create_user_form" ).serialize() )
    	.done(function( data ) {
    		alert("<?php echo _('User created sucsessfully!')?>")
    		$("#create_user_div").hide();
    		$('#create_user_form').trigger("reset");
    		reaload_user_table();
    		
    
    	});
	}
	

	return false;
}
function Delete_user(id)
{

	if( confirm("<?php echo _('Please confirm user removal')?>") == true )
    	$.post( "admin/user_action.php",  { userdel: "1", userid: id } )
    	.done(function( data ) {
    		reaload_user_table();
    
    	});
	
	

	return false;
}
function chahge_password(id)
{


	$("#password_id").val(id);
	 $("#myModal").modal() 
	return false;
}



function update_password()
{
	var pass = $('#password_id').val();
	if(pass)
	{
    	$.post( "admin/user_action.php", $( "#update_password" ).serialize() )
    	.done(function( data ) {
    		alert("<?php echo _('Password is updated!')?>")
    		reaload_user_table();

    
    	});
	}
	return false;
}
function user_c()
{
	$("#create_user_div").toggle()
	
}
function reaload_user_table()
{
	$.get( "admin/get_users.php", function( data ) {


		  $("#user_table_one tbody").html(data);
		});
}


</script>

  <nav class="navbar navbar-expand-lg navbar-light  bg-light" style="background-color: #e3f2fd;">
		
		<a class="navbar-brand" href="#"><?php echo _('Users')?></a>
		
  <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">


      
             <li class="nav-item">
        
             
        <a class="nav-link " href="#" id="navbarDropdownMenuLink" onclick="PrintElem('user_table_one_p','<?php echo _('Print Users')?>')" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <i class="fas fa-print"></i>
          <?php echo _('Print')?>
        </a>
             
        </li>
         <li class="nav-item">
                <a class="nav-link " href="#" id="navbarDropdownMenuLink" onclick="fnExcelexport('user_table_one')" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <i class="far fa-file-excel"></i>
          <?php echo _('Export xls')?>
        </a>
        </li>
        
        <li class="nav-item">
                <a class="nav-link " href="#" id="navbarDropdownMenuLink" onclick="user_c()" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <i class="fas fa-user"></i>
          <?php echo _('Create user')?>
        </a>
        </li>
        
 
        
        
            		
        
        
        
   
      
      
      
    </ul>




      </div>

      
    </nav>
    
    <div id="create_user_div" style="display: none">
    


        
    <form id="create_user_form"  onsubmit="return create_user()">
        <h2><?php echo _('Create user')?>:</h2>
          <fieldset class="form-group">
      <div class="form-group">
      	<div class="col-sm-4">
       	 	<label for="usern1"><?php echo _('Username')?></label>
       	</div>
       <div class="col-sm-8">
        	<input type="text" name="usern" class="form-control" id="usern1" placeholder="">
       </div>
      </div>
      <div class="form-group">
      	<div class="col-sm-4">
        	<label for="password1"><?php echo _('Password')?></label>
        </div>
        <div class="col-sm-8">
        	<input type="password" name="password" class="form-control" id="password1" placeholder="">
        </div>
      </div>
      
        <div class="form-group">
      	<div class="col-sm-4">
        	<label for="name1"><?php echo _('First name')?></label>
        </div>
        <div class="col-sm-8">
        	<input type="text" name="name" class="form-control" id="name1" placeholder="">
        </div>
      </div>
      
      
      
        <div class="form-group">
      	<div class="col-sm-4">
        	<label for="lastname1"><?php echo _('Last name')?></label>
        </div>
       
        <div class="col-sm-8">
        	<input type="text" name="lastname" class="form-control" id="lastname" placeholder="">
        </div>
      </div>
      
      
      
        <div class="form-group row">
        <div class="col-sm-8">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="gridCheck1" value="1"  name="isadmin" checked="checked">
                <label class="form-check-label" for="gridCheck1">
                  <?php echo _('User is admin')?>
                </label>
              </div>
         </div>
          <div class="col-sm-4">
          	 <button type="submit" class="btn btn-primary"><?php echo _('Create user')?></button>
       
        </div>
        
        </div>
        </fieldset>
            	<input type="hidden" name="newuser" value="1" >
        
    </form>

</div>
<br />
<div id="user_table_one_p">
    <table class="table" id="user_table_one">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col"><?php echo _('First name')?></th>
          <th scope="col"><?php echo _('Last name')?></th>
          <th scope="col"><?php echo _('Username')?></th>
          <th scope="col"><?php echo _('Is admin')?></th>
          <th scope="col"><?php echo _('Action')?></th>
        </tr>
      </thead>
      <tbody>
      
      <?php 
      
      
      $result = mysqli_query($link, "SELECT * FROM `users` ");
      
      
      
      while($row = $result->fetch_assoc()) {
          
      ?>
      
        <tr>
      <th scope="row"><?php echo $row['id']?></th>
      <td><?php echo $row['Firstname']?></td>
      <td><?php echo $row['lastname']?></td>
      <td><?php echo $row['Username']?></td>
    <?php   if($row['Is_admin'] == 1){?>
      <td><?php echo _('Yes')?></td>
    <?php }else{?>
      <td><?php echo _('No')?></td>
    <?php }?>
      <td><i class="fas fa-trash" onclick="Delete_user(<?php echo $row['id']?>)"></i> <i onclick="chahge_password(<?php echo $row['id']?>)" class="fas fa-key"></i> </td>
      </tr>
      
      
      <?php }?>
  
 <?php }?>
    

  </tbody>
</table>
</div>

<!-- Trigger the modal with a button -->

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
 <form id="update_password" onsubmit="return update_password()">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title"><?php echo _('Change user password') ?></h4>
      </div>
      
      <div class="modal-body">
      
      
          <label for="inputPassword2" class="sr-only"><?php echo _('Password') ?></label>
          
    		<input type="password" name="password" class="form-control" id="inputPassword2" placeholder="Password">
    		<input type="hidden" name="user_id" id="password_id" value=""> 
    		<input type="hidden" name="change_password"  value="1"> 
      
     
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Close')?></button>
        <button type="submit" class="btn btn-default"><?php echo _('Update')?></button>
      </div>
       </form>
    </div>

  </div>
</div>
