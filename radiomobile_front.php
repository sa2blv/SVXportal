<?php
include "config.php";
include 'function.php';
define_settings();
set_laguage();

mysqli_set_charset($link,"utf8");

if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 )
{
    
    
    
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title> SVX Portal <?php echo PORTAL_VERSION ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<link rel="icon" type="image/png" href="tower.svg">
<link	href="https://fonts.googleapis.com/css?family=Architects+Daughter&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css.css">
<link href="dist/skin/blue.monday/css/jplayer.blue.monday.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="lib/jquery.min.js"></script>
<script type="text/javascript" src="dist/jplayer/jquery.jplayer.min.js"></script>
<script type="text/javascript" src="dist/add-on/jplayer.playlist.min.js"></script>
<link rel="stylesheet" href="lib/css/bootstrap.min.css"
	integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
	crossorigin="anonymous">
<!-- Optional theme -->

<!-- Latest compiled and minified JavaScript -->

<script src="./lib/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="lib/css/bootstrap.min.css">


<link rel="stylesheet" href="jquery-ui.css">
<script src="jquery-ui.js"></script>

<script
	src="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/build/ol.js"></script>
<link rel="stylesheet"
	href="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/css/ol.css">
<!-- fontawesome.com iconpac -->
<link href="./fontawesome/css/all.css" rel="stylesheet">
<!--load all styles -->
<script src="./js/chart/Chart.min.js"></script>

<style type="text/css">
.hideMe {
    display: none;
}




</style>




   <header>
 <nav class="navbar   navbar-dark sidebar_collor">
 <div>
   <a class="navbar-brand" href="#">
    <img src="loggo.png" alt="Logo" style="width:40px;">

  </a>

   <a class="navbar-brand wite_font" href="#">
   
     SVX Portal <?php echo _('Radiomobile interface')?>
   </a>
   
   </div>

  <div class="topnav-right" >
  <a href=""" onclick="window.close()" class="btn btn-outline-success my-2 my-sm-0" id="menu-toggle"><?php echo _('Back')?></a>
  </div>
</nav> 
    </header>
    

<div class="container-fluid">

    <form action="radiomobile.php" method="post" enctype="multipart/form-data">
      <div class="form-group col-6">
      <?php echo _('Select radiomobile zip file  to upload')?> :
      <input type="file" class="form-control" name="fileToUpload" id="fileToUpload">
      </div>
       <input type="hidden" name="site_id" id="site_id" value="<?php echo $_GET['stationid']?>">
        <div class="form-group">
      <input type="submit" value="<?php echo _('upload Zip file')?>" name="<?php echo _('submit')?>">
      </div>
      
    </form>
</div>

</body>
</html>

<?php }
else
    echo _("Not admin");
?>
