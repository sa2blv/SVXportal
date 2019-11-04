<?php
function prepend($string, $orig_filename) {
    $context = stream_context_create();
    $orig_file = fopen($orig_filename, 'r', 1, $context);
    
    $temp_filename = tempnam(sys_get_temp_dir(), 'php_prepend_');
    file_put_contents($temp_filename, $string);
    file_put_contents($temp_filename, $orig_file, FILE_APPEND);
    
    fclose($orig_file);
    unlink($orig_filename);
    rename($temp_filename, $orig_filename);
}


if ($_POST) {
    $sql = file_get_contents('sql/svx.sql');

    $mysqli = new mysqli( $_POST['server'], $_POST['mqluser'], $_POST['password'] , $_POST['Database']);
    if (mysqli_connect_errno()) { /* check connection */
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    /* execute multi query */
    if ($mysqli->multi_query($sql)) {
        echo "success";
            
        $current = "<?php\n
        ";
        $current .= 
        '$host = "'.$_POST['server'].'";'."\n".'
        $user ="'.$_POST['mqluser'].'";'."\n".'
        $password ="'.$_POST['password'].'";'."\n".'
        $db="'.$_POST['password'].'";'."\n";

        
        $current .= "\n".'$dir="'. $_POST['recordingfolder'].'"'."\n";
        $current .= '$serveradress="'. $_POST['icecst'].'".'-"\n";
        
        echo '<pre>';
        echo $current;
        echo '</pre>';
        prepend($current,"config.php");
        
      
        
        
        
        
    } else {
        echo "error";
    }
} else {

    ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SVX Portal 2.1 Beta</title>
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

<link rel="stylesheet" href="lib/css/bootstrap.min.css"
	integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
	crossorigin="anonymous">
<!-- Optional theme -->

<!-- Latest compiled and minified JavaScript -->

<script src="./lib/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="lib/css/bootstrap.min.css">


</head>
<body>
	<div>
		<h1>Svxportal installer</h1>
		<form action="install.php" method="post">
			<div class="form-group">
				<label for="server">MYsql Server </label> <input
					type="text" class="form-control" name="server" id="server"
					placeholder="127.0.0.1">
			</div>

			<div class="form-group">
				<label for="mqluser">Mysql Usermame</label> <input
					type="text" class="form-control" name="mqluser" id="mqluser"
					placeholder="Svxportal">
			</div>

			<div class="form-group">
				<label for="Mysqlpassword">Password</label> <input type="password"
					class="form-control" id="Mysqlpassword"  name="password" placeholder="Password">
			</div>
			

			<div class="form-group">
				<label for="Database">MYSQL DB</label> <input
					type="text" class="form-control" name="Database" id="Database"
					placeholder="">
			</div>

			<div class="form-group">
				<label for="formGroupExampleInput">Reflector proxy adress</label> <input
					type="text" class="form-control" name="proxy" id="formGroupExampleInput"
					placeholder="http://www.svxportal.se/proxy">
			</div>

			<div class="form-group">
				<label for="formGroupExampleInput1">Stemingserver adress</label> <input
					type="text" class="form-control" name="icecst" id="formGroupExampleInput1"
					placeholder="http://www.svxportal.se/proxy">
			</div>
	
	
				<div class="form-group">
				<label for="formGroupExampleInput2">Svx recording folder</label> <input
					type="text" class="form-control" name="recordingfolder" id="formGroupExampleInput2"
					placeholder="/var/www/svx/svxrecording">
			</div>		
			


			<button type="submit" class="btn btn-primary">Install</button>
		</form>
	</div>
</body>
</html>

<?php }?>
