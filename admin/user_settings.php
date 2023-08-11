<?php
include "config.php";
include 'function.php';
define_settings();
set_laguage();

mysqli_set_charset($link,"utf8");

if(!$_SESSION["loginid"])
{
    include "admin/login.php";
    exit(-1);
   
}



$default_image ="user.svg";

// get user data 
$user_id = $_SESSION["loginid"];
$result = mysqli_query($link, "SELECT * FROM `users` WHERE `id` = $user_id ORDER BY `id` ASC limit 1");

// Numeric array
$i = 0;

// Associative array
$user_sql = mysqli_fetch_array($result, MYSQLI_ASSOC);



if($user_sql['image_url'] != "")
{
    $default_image =$user_sql['image_url'];
    
}

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


body{
    background: #f5f5f5;

}

.ui-w-80 {
    width: 80px !important;
    height: auto;
}

.btn-default {
    border-color: rgba(24,28,33,0.1);
    background: rgba(0,0,0,0);
    color: #4E5155;
}

label.btn {
    margin-bottom: 0;
}

.btn-outline-primary {
    border-color: #26B4FF;
    background: transparent;
    color: #26B4FF;
}

.btn {
    cursor: pointer;
}

.text-light {
    color: #babbbc !important;
}

.btn-facebook {
    border-color: rgba(0,0,0,0);
    background: #3B5998;
    color: #fff;
}

.btn-instagram {
    border-color: rgba(0,0,0,0);
    background: #000;
    color: #fff;
}

.card {
    background-clip: padding-box;
    box-shadow: 0 1px 4px rgba(24,28,33,0.012);
}

.row-bordered {
    overflow: hidden;
}

.account-settings-fileinput {
    position: absolute;
    visibility: hidden;
    width: 1px;
    height: 1px;
    opacity: 0;
}
.account-settings-links .list-group-item.active {
    font-weight: bold !important;
    color:  blue !important;
}
html:not(.dark-style) .account-settings-links .list-group-item.active {
    background: transparent !important;
}
.account-settings-multiselect ~ .select2-container {
    width: 100% !important;
}
.light-style .account-settings-links .list-group-item {
    padding: 0.85rem 1.5rem;
    border-color: rgba(24, 28, 33, 0.03) !important;
}
.light-style .account-settings-links .list-group-item.active {
    color: #4e5155 !important;
}
.material-style .account-settings-links .list-group-item {
    padding: 0.85rem 1.5rem;
    border-color: rgba(24, 28, 33, 0.03) !important;
}
.material-style .account-settings-links .list-group-item.active {
    color: #4e5155 !important;
}
.dark-style .account-settings-links .list-group-item {
    padding: 0.85rem 1.5rem;
    border-color: rgba(255, 255, 255, 0.03) !important;
}
.dark-style .account-settings-links .list-group-item.active {
    color: #fff !important;
}
.light-style .account-settings-links .list-group-item.active {
    color: #4E5155 !important;
}
.light-style .account-settings-links .list-group-item {
    padding: 0.85rem 1.5rem;
    border-color: rgba(24,28,33,0.03) !important;
}








</style>


</head>

<body>



<header>
 <nav class="navbar   navbar-dark sidebar_collor">
 <div>
   <a class="navbar-brand" href="#">
    <img src="loggo.png" alt="Logo" style="width:40px;">

  </a>

   <a class="navbar-brand wite_font" href="#">
   
     SVX Portal <?php echo _(' Account settings ')?>
   </a>
   
   </div>

  <div class="topnav-right" >
  <a href="index.php" onclick="" class="btn btn-outline-success my-2 my-sm-0" id="menu-toggle"><?php echo _('Back')?></a>
  </div>
</nav> 
</header>

 <div class="container-fluid">
 

<?php if($_GET['error'] == 2){?>
<br />
<div class="alert alert-danger" role="alert">
  <?php echo _('Passwords incorect')?>
</div>



<?php }elseif($_GET['sucess'] == 1){?>
<br />
<div class="alert alert-success" role="alert">
  <?php echo _('Settings updated ')?>
</div>

<?php }else{?>

<br />
<?php }?>



<form action="admin/update_user.php" method="post">



    <div class="card overflow-hidden">
      <div class="row no-gutters row-bordered row-border-light">
        <div class="col-md-3 pt-0">
          <div class="list-group list-group-flush account-settings-links">
            <a class="list-group-item list-group-item-action <?php if($_GET['password'] !="1") echo "active";?>" data-toggle="list" href="#account-general"><?php echo _('General')?></a>
            <a class="list-group-item list-group-item-action <?php if($_GET['password'] =="1") echo "active";?>" data-toggle="list" href="#account-change-password"><?php echo _('Change password')?></a>
          </div>
        </div>
        <div class="col-md-9">
          <div class="tab-content">
            <div class="tab-pane fade active show" id="account-general">

              <div class="card-body media align-items-center">
                <img src="<?php echo $default_image;?>" alt="" class="d-block ui-w-80">
                <div class="media-body ml-4">
                <?php 
                /*
                  <label class="btn btn-outline-primary">
                    <?php echo _("Upload new photo");?>
                    <input type="file" class="account-settings-fileinput">
                  </label> &nbsp;
                  <button type="button" class="btn btn-default md-btn-flat"> <?php echo _("Reset")?></button>

                  <div class="text-light small mt-1"> <?php echo _("Allowed JPG, GIF or PNG. Max size of 800K")?></div>
                                  */
                ?>
                </div>

              </div>
              <hr class="border-light m-0">

              <div class="card-body">
              
                 <div class="form-group">
                  <label class="form-label"><?php echo _('Username')?> </label>
                  <input  disabled="disabled" name="username" type="text" class="form-control mb-1" value="<?php echo $user_sql['Username'];?>">
                </div>
                
                
              
              
                <div class="form-group">
                  <label class="form-label"><?php echo _('Firstname')?> </label>
                  <input type="text" class="form-control mb-1" name="Firstname"  value="<?php echo $user_sql['Firstname'];?>">
                </div>
                <div class="form-group">
                  <label class="form-label"><?php echo _('Lastname')?></label>
                  <input type="text" class="form-control" name="lastname"  value="<?php echo $user_sql['lastname'];?>">
                </div>
                <div class="form-group">
                  <label class="form-label"><?php echo _('E-mail')?></label>
                  <input type="text" class="form-control mb-1" name="email" value="<?php echo $user_sql['email'];?>">
                </div>
                
                 <div class="form-group">
                  <label class="form-label"><?php echo _('Image URL')?></label>
                  <input type="text" class="form-control  mb-1"  name="image_url" value="<?php echo $user_sql['image_url'];?>">
                </div>
                
       
              </div>

            </div>
            <div class="tab-pane fade" id="account-change-password">
              <div class="card-body pb-2">


                <div class="form-group">
                  <label class="form-label"><?php echo _('New password')?></label>
                  <input type="password" name="password1" class="form-control">
                </div>

                <div class="form-group">
                  <label class="form-label"><?php echo _('Repeat new password')?></label>
                  <input type="password" name="password2" class="form-control">
                </div>

              </div>
            </div>
            
        </div>
      </div>
    </div>
</div>
    <div class="text-right mt-3">
      <button type="submit" class="btn btn-primary"><?php echo _('Save changes')?></button>&nbsp;
      <button type="button" class="btn btn-default"><?php echo _('Cancel')?></button>
    </div>


  </form>
  </div>
  








  





</body>
</html>
