<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SVX Portal installer</title>
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
<nav class="navbar   navbar-dark sidebar_collor justify-content-between">
      
       
        <div>
   <a class="navbar-brand" href="#">
    <img src="loggo.png" alt="Logo" style="width:40px;">

  </a>

   <a class="navbar-brand wite_font" href="#">
   
     SVX Portal installer   </a>
   
   </div>
   
   
   
   
   


      
    </nav>
    
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
        

    ?>
    <script type="text/javascript">

    function OpenPopupWindow()
    {
     var url = "selftest.php";
    myRef = window.open(url ,'mywin','left=20,top=20,width=800,height=600,toolbar=0,resizable=0');
    myRef.focus()


    }

    

    </script>
    <div class="container-fluid">
    
    <?php     
        echo "<h2>Sucsess to add  database contents</h2> <br> <hr />";
        
        echo "<h3>Stage 1</h3>  Now add this to your <b>".getcwd()."/config.php</b>"
       ?>
    
    
        <?php 
        
        $myfile = fopen("config_example.php", "r") or die("Unable to open file!");
        $config_text =  fread($myfile,filesize("config_example.php"));
        
        
        $config_text = str_replace("%DB_HOST%", $_POST['server'],  $config_text);
        $config_text = str_replace("%DB_USER%", $_POST['mqluser'],  $config_text);
        $config_text = str_replace("%DB_PASSWORD%", $_POST['password'],  $config_text);
        $config_text = str_replace("%DB_DB%", $_POST['Database'],  $config_text);
        $config_text = str_replace("%Stream_url%", $_POST['icecst'],  $config_text);
        
        $config_text = str_replace("%Recording_folder%", $_POST['recordingfolder'],  $config_text);
        $config_text = str_replace("%Proxy_Serveradress%", $_POST['proxy'],  $config_text);
        
        echo"<pre>";
        echo htmlspecialchars($config_text);
        echo "</pre>";
    


        
        echo " <hr /> <h3>Stage 2</h3>  Now add this to your <b>".getcwd()."/reflector_proxy/config.php</b>
         or to the location of the reflector_proxy";
    
        echo"<pre>";
        echo htmlspecialchars( '
        <?php
        $Svx_reflector_address = "'.$_POST['reflector_adress'].'";  
        ?>
        ');
        echo "</pre>";
        
        
        echo " <hr /> <h3>Stage 3</h3>   ";
        
        echo '
        <button type="button" class="btn btn-primary btn-lg" onclick="OpenPopupWindow()">Self test</button>
        ';
        
        echo "Run self test to test your installation";
        
        echo " <hr /> <h3>Stage 4</h3>   ";
        
        echo "Add followingline to crotab";
        
        ?>
        <pre>
screen  -d -m bash -c  'cd <?php echo getcwd()?>; watch -n 20  php station_heartbeat.php;'
screen  -d -m bash -c  'cd <?php echo getcwd()?>; watch -n 1  php logdeamon.php;'
        </pre>
    
        <?php
        
        
        
        
        
        
        echo " <hr /> <h3>Stage 5</h3>   ";
        
        
       echo '<a class="btn btn-primary" href="admin.php" role="button">Go to admin interface</a>';
       
       echo "<p ></p>";
        
        
       echo "</div>";
        
        
        
        
        /*
            
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
        prepend($current,"config1.php");
        */
      
        
        
        
        
    } else {
        echo "Error can't login to db";
    }
    
    
} else {

    ?>

    
    <div class="container-fluid">
      <div class="row">
    	<div class="col-6">
    		<h1>Svxportal installer</h1>
    		<form action="install.php" method="post">
    		
    		<hr />
    			<div class="form-group">
    			<label for="server">MYSQL Server </label> <input
    					type="text" class="form-control" name="server" id="server"
    					placeholder="127.0.0.1">
    			</div>
    
    			<div class="form-group">
    				<label for="mqluser">MYSQL Usermame</label> <input
    					type="text" class="form-control" name="mqluser" id="mqluser"
    					placeholder="Svxportal">
    			</div>
    
    			<div class="form-group">
    				<label for="Mysqlpassword">MYSQL Password</label> <input type="password"
    					class="form-control" id="Mysqlpassword"  name="password" placeholder="Password">
    			</div>
    			
    
    			<div class="form-group">
    				<label for="Database">MYSQL DB</label> <input
    					type="text" class="form-control" name="Database" id="Database"
    					placeholder="">
    			</div>
   		 <hr />
    			<div class="form-group">
    				<label for="formGroupExampleInput">Reflector_proxyserver URL</label> <input
    					type="text" class="form-control" name="proxy" id="formGroupExampleInput"
    					placeholder="http://www.svxportal.se/proxy" value="http://<?php echo $_SERVER['HTTP_HOST']?>/reflector_proxy">
    			</div>
    
    			<div class="form-group">
    				<label for="formGroupExampleInput1">Icecast-server URL</label> <input
    					type="text" class="form-control" name="icecst" id="formGroupExampleInput1"
    					value="http://<?php echo $_SERVER['HTTP_HOST']?>:8000/live">
    			</div>
    	
    	
    				<div class="form-group">
    				<label for="formGroupExampleInput2">Svx recording folder</label> <input
    					type="text" class="form-control" name="recordingfolder" id="formGroupExampleInput2"
    					value="<?php echo getcwd()?>svxrecording">
    			</div>		
 
 
     			<div class="form-group">
    				<label for="reflector_adress">Reflector adress</label> <input
    					type="text" class="form-control" name="reflector_adress" id="reflector_adress"
    					value=http://reflektorserver:8080/status">
    			</div>		
    			
    			
    			   			
    
    
    			<button type="submit" class="btn btn-primary">Next step</button>
    		</form>
    	</div>
    	<div class="col-6">
    	<br>
    	<br>
    	<div class="border border-primary">
<pre>
### Installing

check that you have installed to php packages

 libapache2-mod-php7.0 php7.0 php7.0-common php7.0-curl php7.0-dev php7.0-gd php-pear php-imagick php7.0-mcrypt php7.0-mysql php7.0-ps php7.0-xsl php7.0-zip
 
 php 8 work aswell 
 
 check that the proxy is activated.
 
 [GLOBAL]
#CFG_DIR=svxreflector.d
TIMESTAMP_FORMAT="%c"
LISTEN_PORT=5300
#SQL_TIMEOUT=600
#SQL_TIMEOUT_BLOCKTIME=60
#CODECS=OPUS
TG_FOR_V1_CLIENTS=999
#RANDOM_QSY_RANGE=12399:100
<b>HTTP_SRV_PORT=8080</b>




Then filout the form to intall datadbase and create config.php




</pre>


    	</div>
    	</div>
	</div>
    </div>
</body>
</html>

<?php }?>
