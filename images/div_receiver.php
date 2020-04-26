
<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=Cp1252">
<title>Receiver</title>


<link
	href="https://fonts.googleapis.com/css?family=Architects+Daughter&display=swap"
	rel="stylesheet">
<link rel="stylesheet" href="css.css">

<!-- Latest compiled and minified JavaScript -->
<script type="text/javascript" src="lib/jquery.min.js"></script>

<script src="./lib/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="lib/css/bootstrap.min.css">
<script src="./js/div_recivers.js"></script>

<script>
    
    $(document).ready(function(){
        
        call_svxrefelktor();

        $.getJSON( "<?php echo $serveradress ?>", function( data ) {

   

            
          for(var k in data.nodes){

                	$('#selects').append($('<option>', {
                	    value: k,
                	    text: k
                	}));
                }
            

        });
        
        
    });

        
        </script>



</head>

<body>
        
        <?php
        ?>


	
	<div class="container">
		<nav class="navbar navbar-light bg-dark">
			<select id="selects" onchange="update_filter(this.value)">
				<option value="">-- All --</option>
			</select>

		</nav>
	</div>
	<div id="holder" class="container"></div>


</body>


</html>
