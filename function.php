<?php
session_start();
$current_lagnuge="";
function set_laguage() {
    global $current_lagnuge;
    // Check if gettext is enable
    if(!function_exists("gettext")) die("gettext is not enable");
    
    
    $langs = array();
    
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        // break up string into pieces (languages and q factors)
        preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);
        
        if (count($lang_parse[1])) {
            // create a list like "en" => 0.8
            $langs = array_combine($lang_parse[1], $lang_parse[4]);
            
            // set default to 1 for any without q factor
            foreach ($langs as $lang => $val) {
                if ($val === '') $langs[$lang] = 1;
            }
            
            // sort list based on value
            arsort($langs, SORT_NUMERIC);
        }
    }
    

    
    switch (key($langs)) 
    {
        case 'sv':
            $lang ="sv_SE";
            break;
        case 'en':
            $lang ="en_GB";
            break;
        case 'tr':
            $lang ="tr_TR";
            break;
        case 'no':
            $lang ="no_NO";
            break;
        case 'de':
            $lang ="de_DE";
            break;
        case 'uk':
            $lang ="uk_UA";
            break;
        case 'fr':
            $lang ="fr_FR";
            break;
        case 'pl':
            $lang ="pl_PL";
            break;
        default:
            $lang = key($langs);
            break;
    }
        
     
        
    
       
        
        $lang = str_replace("-", "_", $lang);
        $lang= trim($lang);
        
        
    
    
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
    
    $current_lagnuge= $locale;

    putenv("LANG=".$locale); //not needed for my tests, but people say it's useful for windows
    putenv("LANGUAGE=".$locale); 
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
function ctcss_array()
{
    $ctcss[] = 67.0;
    $ctcss[] = 69.3 ;
    $ctcss[] = 71.9 ;
    $ctcss[] = 74.4 ;
    $ctcss[] = 77.0 ;
    $ctcss[] = 79.7 ;
    $ctcss[] = 82.5 ;
    $ctcss[] = 85.4 ;
    $ctcss[] = 88.5 ;
    $ctcss[] = 91.5 ;
    $ctcss[] = 94.8 ;
    $ctcss[] = 97.4 ;
    $ctcss[] = 100.0 ;
    $ctcss[] = 103.5 ;
    $ctcss[] = 107.2 ;
    $ctcss[] = 110.9 ;
    $ctcss[] = 114.8 ;
    $ctcss[] = 118.8 ;
    $ctcss[] = 123.0 ;
    $ctcss[] = 127.3 ;
    $ctcss[] = 131.8 ;
    $ctcss[] = 136.5 ;
    $ctcss[] = 141.3 ;
    $ctcss[] = 146.2 ;
    $ctcss[] = 151.4 ;
    $ctcss[] = 156.7 ;
    $ctcss[] = 162.2 ;
    $ctcss[] = 167.9 ;
    $ctcss[] = 173.8 ;
    $ctcss[] = 179.9 ;
    $ctcss[] = 186.2 ;
    $ctcss[] = 192.8 ;
    $ctcss[] = 203.5 ;
    $ctcss[] = 210.7 ;
    $ctcss[] = 218.1 ;
    $ctcss[] = 233.6 ;
    $ctcss[] = 241.8 ;
    $ctcss[] = 250.3 ;


    return $ctcss;
    
    
}
function return_flag($lang)
{
    $class ="d-none d-xl-inline-flex d-lg-inline-flex ";
    
    if($lang == "sv_SE")
        echo '<img  src="images/flags/se.svg" width="25px" alt="Se"> <span class="'.$class.'">SWE</span>';
    elseif($lang == "nb_NO")
        echo '<img  src="images/flags/no.svg" width="25px" alt="NO"> <span class="'.$class.'">NO</span>';
    elseif($lang == "uk_UA")
        echo '<img  src="images/flags/ua.svg" width="25px" alt="uk"> <span class="'.$class.'">UA</span>';
    elseif($lang == "it_IT")
        echo '<img  src="images/flags/it.svg" width="25px" alt="it"> <span class="'.$class.'">IT</span>';
    elseif($lang == "tr_TR")
        echo '<img  src="images/flags/tr.svg" width=25px" alt="tr_TR"> <span class="'.$class.'">TR</span>';
    elseif($lang == "de_DE")
        echo '<img  src="images/flags/de.svg" width=25px" alt="tr_TR"> <span class="'.$class.'">DE</span>';
    elseif($lang == "fr_FR")
        echo '<img  src="images/flags/fr.svg" width=25px" alt="tr_TR"> <span class="'.$class.'">FR</span>';
    elseif($lang == "pl_PL")
        echo '<img  src="images/flags/pl.svg" width=25px" alt="tr_TR"> <span class="'.$class.'">PL</span>';
    else
        echo '<img  src="images/flags/gb.svg" width="25px" alt="GB" style=""> <span class="'.$class.'">ENG</span>';
    

}
function DTMF_Catgory()
{
    $Dmtf[0]=_('General');
    $Dmtf[1]=_('Echolink');
    $Dmtf[2]=_('Reflektor');
    $Dmtf[3]=_('Shortcut');
    $Dmtf[4]=_('Macro');
    
    
    
    
    return $Dmtf;
}
function Operation_message_type()
{
    $Dmtf[0]=_('Info');
    $Dmtf[1]=_('Warning');
    $Dmtf[2]=_('Fault');
    $Dmtf[3]=_('Done');

    

    
    return $Dmtf;
}




function __autoload_driver($className)
{

    require_once 'driver/card/'.$className.'.php';
}


function check_premission_station($staion_id,$user_id)
{
    
    global $link;
    $staion_id = $link->real_escape_string($staion_id);
    $user_id =$link->real_escape_string($user_id);
    
    if ($result = $link->query("SELECT RW FROM `User_Premission` where Station_id='$staion_id' AND User_id = '$user_id' ")) {
        
        /* determine number of rows result set */
        $row_cnt = $result->num_rows;
        
        return $row_cnt;
        
        
    }
    
    return 0;
    
  
    
}
function check_premission_station_RW($staion_id,$user_id)
{
    
    global $link;
    $staion_id = $link->real_escape_string($staion_id);
    $user_id =$link->real_escape_string($user_id);
    
    if ($result = $link->query("SELECT RW FROM `User_Premission` where Station_id='$staion_id' AND User_id = '$user_id' AND RW >= 1 ")) {
        
        /* determine number of rows result set */
        $row_cnt = $result->num_rows;
        
        return $row_cnt;
        
        
    }
    
    return 0;
    
    
}
function page_id_to_staion_id($page_id)
{
    global $link;
    $result = mysqli_query($link, "SELECT  Station_id FROM `Infotmation_page` where id='$page_id'");
    
    
    
    while($row = $result->fetch_assoc()) {
        
        return  $row['Station_id'];

            
        }
    
    
}

function DTMF_ID_TO_STATION($dtmfid)
{
    global $link;
    $result = mysqli_query($link, "SELECT * FROM `Dtmf_command` where id= '$dtmfid'  ORDER BY `Station_id` ASC ");
    
    
    
    while($row = $result->fetch_assoc()) {
        
        return  $row['Station_id'];
        
        
    }
    
    
}
/*
 * https://www.splitbrain.org/blog/2008-09/18-calculating_color_contrast_with_php
 * 
 * 
 */
function brghtdiff($R1,$G1,$B1,$R2,$G2,$B2){
    $BR1 = (299 * $R1 + 587 * $G1 + 114 * $B1) / 1000;
    $BR2 = (299 * $R2 + 587 * $G2 + 114 * $B2) / 1000;
    
    return abs($BR1-$BR2);
}

function hex2rgb( $colour ) {
    if ( $colour[0] == '#' ) {
        $colour = substr( $colour, 1 );
    }
    if ( strlen( $colour ) == 6 ) {
        list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
    } elseif ( strlen( $colour ) == 3 ) {
        list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
    } else {
        return false;
    }
    $r = hexdec( $r );
    $g = hexdec( $g );
    $b = hexdec( $b );
    return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

function return_diff_to_darkness($collor)
{
    if($collor == '')
        return 0;
    else
    {
        $colloar_array =hex2rgb(trim($collor));
    }
    return brghtdiff($colloar_array['red'],$colloar_array['green'],$colloar_array['blue'],0,0,0);
    
}

function translate_folder_page($url)
{
    global $Use_translate_default_lang;
    
    if($Use_translate_default_lang == $_SESSION['languge'])
    {

        return $url;
        
    }
    else
    {

        $url = str_replace(".htm", "", $url);

        return $url."_" .$_SESSION['languge'].".htm";
        
    }
   
    

    
}

<<<<<<< HEAD

function detect_empty_cache_table()
{
    global $link;
    $nummber = $link->query("SELECT COUNT(*) as c FROM trafic_day_statistics ")->fetch_object()->c;
    
    if($nummber  >0 )
        return true;
    
    return false;
    
}


=======
>>>>>>> master












?>