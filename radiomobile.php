<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'config.php';
include 'function.php';

$target_dir = "/tmp/svxportal/";

$covrige_folder ="/var/www/svx/covrige";


$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image



 function delTree($dir) {
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
} 




if($_SESSION['is_admin'] >0 && $_SESSION['loginid'] >0 )
{

    if(isset($_POST["submit"]) && $_POST['site_id'] !="" ) {
        
        
       
        $unzip = new ZipArchive;
        $out = $unzip->open($_FILES["fileToUpload"]["tmp_name"]);
        if ($out === TRUE) {
            $target_dir ="/tmp/svxportal/".$_FILES["fileToUpload"]["tmp_name"];
            //echo getcwd();
            // requers tmp foler not for widows server
            $unzip->extractTo($target_dir);
            $unzip->close();

            
            $radiomobie_str_to_db = "";
    
            
            foreach( glob( $target_dir.'/*.html' ) as $html_file ) {
                //echo $html_file . '<br />';
                
                
                $myfile = fopen($html_file, "r") or die("Unable to open file!");
                while(!feof($myfile)) {
                    $line = fgets($myfile);
                    
                    if (str_contains($line, 'rmap1=addimage')) {
                        
                        //echo $line;
                        $rm_funtion_1 =  explode( "rmap1=", $line, 2);
                        $rm_funtion_2 =  explode( ";", $rm_funtion_1[1], 2);
                        $radiomobie_str_to_db = $rm_funtion_2[0].";";
                       // var_dump($radiomobie_str_to_db);
                        
                        $radiomobie_str_to_db = $link->real_escape_string($radiomobie_str_to_db);
                        $station =  $link->real_escape_string($_POST['site_id']);
                        
                        
                        
                        $link->query("  DELETE FROM `covrige` WHERE `Name` = '$station';");
                        
                        $link->query(" INSERT INTO `covrige` (`Id`, `Name`, `Radiomobilestring`) VALUES (NULL, '$station', '$radiomobie_str_to_db'); ");

                    }
                    
                  
                                  
                }
                
                
                chdir($target_dir);
                if($radiomobie_str_to_db != "")
                {
                    foreach( glob( '*.png' ) as $html_file )
                    {

                         copy($html_file, $covrige_folder."/".$html_file);
                        
                    }
                }
                
                fclose($myfile);
                
                
                delTree($target_dir);
                
                
                
                
    
    
                
            }
            
            ?>
            <script>
            alert("<?php echo _('Uplad sucess!'); ?>");
            window.close();
            
            </script>
            <?php 
            
            
            
            
            
            
            
            
            
        } else {
            echo '<b>'._('Error').'</b>';
        }
        
        
        
        
        
        
        
        
    
    }
 
}
else
{
 echo "not admin";   
}



