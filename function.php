<?php
session_start();
function set_laguage() {
    // Check if gettext is enable
    if(!function_exists("gettext")) die("gettext is not enable");
    
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5);
    // Processes \r\n's first so they aren't converted twice.
    $lang = str_replace("-", "_", $lang);
    $lang= trim($lang);
    
    $directory = dirname(__FILE__).'/locale';
    $domain = 'svxportal';

    $locale =$lang; //like pt_BR.utf8";
    if($_SESSION['languge'])
    {
        $locale = $_SESSION['languge'];

    }
    
    
    putenv("LANG=".$locale); //not needed for my tests, but people say it's useful for windows
    
    setlocale( LC_MESSAGES, $locale);
    bindtextdomain($domain, $directory);
    textdomain($domain);
    bind_textdomain_codeset($domain, 'UTF-8');
    
    
}
function post_languge()
{
    if($_POST['locate_lang'])
    {
        $_SESSION['languge'] = $_POST['locate_lang'];
    }
    
}
function startdate($stardate) {
    
    if($stardate != "")
    {
        $timestamp =strtotime($stardate);  
        echo "minDate: new Date(".date("Y",$timestamp).",".(date("m",$timestamp)-1).", ".date("d",$timestamp)."),";
    }
}

function get_oldest_file()
{
    $directory="./svxrecording/";
    
    $smallest_time=INF;
    
    $oldest_file='';
    
    if ($handle = opendir($directory)) {
        
        while (false !== ($file = readdir($handle))) {
            
            $time=filemtime($directory.'/'.$file);
            
            if (is_file($directory.'/'.$file)) {
                
                if ($time < $smallest_time &&  strpos($file, '.ogg')) {
                    $oldest_file = $file;
                    $smallest_time = $time;
                }
            }
        }
        closedir($handle);
    }
    

    if($smallest_time !=INF)
    {

        echo "minDate: new Date(".date("Y",$smallest_time).",".(date("m",$smallest_time)-1).", ".date("d",$smallest_time)."),";
    }
}
function define_settings()
{
    global $link;
    $result = mysqli_query($link, "SELECT * FROM `Settings` WHERE 1  ");
    
    // Numeric array
    
    // Associative array
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        
        define($row['Define'], $row['value']);

     }

    
    
    
    

    
}
function enable_debug()
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
}
function node_down()
{
    
    
}
function node_up()
{
    global $link;
    
    if ($result = $link->query("SELECT Code, Name FROM Country ORDER BY Name")) {
        
        /* determine number of rows result set */
        $row_cnt = $result->num_rows;
        
        return $row_cnt;
        

    }
    
    return 0;

}
?>