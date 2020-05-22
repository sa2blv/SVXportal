<?php 
//var_dump($_SESSION);
?>
<script type="text/javascript">
function login_form_sumbit()
{
	$.post( "login.php", $( "#login_form" ).serialize() )
	.done(function( data ) {
		console.log(data);
		if(data.trim() == "true"){
			 location.reload(); 
		}else
			alert("<?php echo _('Wrong username or password')?>")

	});
	

	return false;
}

</script>

<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header">
				<h3><?php echo _('Sign In')?></h3>

			</div>
			<div class="card-body">
				<form   id="login_form" onsubmit="return login_form_sumbit() ">
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>
						<input type="text" id ="login" name="login" class="form-control" placeholder="<?php echo _('Username');?>">
						
					</div>
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
						<input type="password" id="password" name ="password"  class="form-control" placeholder="<?php echo _('Password')?>">
					</div>

					<div class="form-group">
						<input type="submit" value="<?php echo _('Login')?>" class="btn float-right login_btn">
					</div>
				</form>
			</div>

		</div>
	</div>
</div>