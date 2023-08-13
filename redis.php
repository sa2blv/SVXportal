<?php


$redis= "";

$Resid_key_multisite ="";

if (defined('USE_REDIS')) 
{

    $redis = new Redis();
    $redis->connect($red_Host, $red_Port);
    $redis->auth($red_Auth);
    if (defined('USE_REDIS_Multiuite_index'))
    {

        $Resid_key_multisite = USE_REDIS_Multiuite_index."_";
        
    }

}




function Redis_Get_key($key) 
{
    global $redis;
    global $Resid_key_multisite;
    
    $ar = $redis->get($Resid_key_multisite.$key);
    return $ar;
}
function Redis_Set_Key($key,$value)
{
    global $redis;
    global $Resid_key_multisite;
    $redis->set($Resid_key_multisite.$key, $value); 
    
   
}
function Redis_Set_Key_ser($key,$array,$time = '')
{
    global $redis;
    global $Resid_key_multisite;
    $redis->set($Resid_key_multisite.$key, serialize($array),$time);
  
    
}



function Redis_Set_Key_time($key,$value,$time = 10)
{
    global $redis;
    global $Resid_key_multisite;
    $redis->set($Resid_key_multisite.$key, $value,$time);
    
    
}
function Redis_key_exist($key)
{
    global $redis;
    global $Resid_key_multisite;
    return  $redis->exists($Resid_key_multisite.$key);
}

function Redis_Get_ser($key)
{
    global $redis;
    global $Resid_key_multisite;
    
    $ar = $redis->get($Resid_key_multisite.$key);
    
    return unserialize($ar);
}


function Redis_key_exist_enable($key)
{

    
    if (defined('USE_REDIS'))
    {
        global $redis;
        global $Resid_key_multisite;
        return  $redis->exists($Resid_key_multisite.$key);
        
        
    }
    else
    {
        return false;
    }
    
    return false;
}

function sql_to_array_redis_cahce($key,$sql,$time = 500)
{
    global $link;

  
    
    $sql_data =array();
    
    if(Redis_key_exist_enable($key) )
    {

        $sql_data =Redis_Get_ser ($key);
    }
    else
    {
        
        $sqla = $link->query($sql);
        
        while($row = $sqla->fetch_assoc())
        {
            $sql_data[] = $row;
            
            
        }
        
        Redis_Set_Key_ser($key,$sql_data,$time);
        
        
    }
    
    return  $sql_data;
    
}







