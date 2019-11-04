<?php
//header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header("Access-Control-Allow-Origin: *");

function get_fcontent( $url,  $javascript_loop = 0, $timeout = 5 ) {
    $url = str_replace( "&amp;", "&", urldecode(trim($url)) );

    $cookie = tempnam ("/tmp", "CURLCOOKIE");
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
    curl_setopt( $ch, CURLOPT_URL, $url );

    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_ENCODING, "" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );    # required for https urls
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    $cookies = tempnam('/tmp','cookie.txt');
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies); 
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT , 1);
    
    $content = curl_exec( $ch ); 
    $response = curl_getinfo( $ch );
    curl_close ( $ch );
    unlink($cookies);
    if ($response['http_code'] == 301 || $response['http_code'] == 302) {
        ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");

        if ( $headers = get_headers($response['url']) ) {
            foreach( $headers as $value ) {
                if ( substr( strtolower($value), 0, 9 ) == "location:" )
                    return get_url( trim( substr( $value, 9, strlen($value) ) ) );
            }
        }
    }
    // clean temp from cookie data
    array_map('unlink', glob("/tmp/cookie.txt*"));
    array_map('unlink', glob("/tmp/CURLCOOKIE*"));


    if (    ( preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value) ) && $javascript_loop < 5) {
        return get_url( $value[1], $javascript_loop+1 );
    } else {
        return array( $content, $response );
    }
}


function getTime($path){
    clearstatcache($path);
    $dateUnix = shell_exec('stat --format "%y" '.$path);
    $date = explode(".", $dateUnix);
    return filemtime($path).".".substr($date[1], 0, 8);
}






// chache 
$url = $_SERVER["SCRIPT_NAME"];
$break = Explode('/', $url);
$file = $break[count($break) - 1];

$cachefile = '/tmp/cached-'.substr_replace($file ,"",-4).'.json';
$cachetime = 0.2;

// Serve from the cache if it is younger than $cachetime
if (file_exists($cachefile) && microtime(true)  - $cachetime < getTime($cachefile)) {
    //echo "<!-- Cached copy, generated ".date('H:i', filemtime($cachefile))." -->\n";
    readfile($cachefile);
    flush();
    exit;
}
else
{
$url = "http://172.17.2.151:8080/status"; // path to your JSON file
$data = get_fcontent($url); // put the contents of the file into a variable

// Cache the contents to a cache file
$cached = fopen($cachefile, 'w');
fwrite($cached, $data[0]);
fclose($cached);
echo $data[0];
flush();

}







?>

