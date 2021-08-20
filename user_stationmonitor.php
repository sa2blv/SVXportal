<?php


$user_id= $_SESSION['loginid'];
$result = mysqli_query($link, "SELECT * FROM User_Premission LEFT JOIN RefletorStations ON RefletorStations.ID = User_Premission.Station_id WHERE User_Premission.User_id ='$user_id' ");
?>
var talkgroups_active = new Array();

function monitor_station_data()
{
<?php 
    // Associative array
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
    {
    ?>
    
    talkgroups_active["<?php echo $row['Callsign']?>"] = new Array();
    talkgroups_active["<?php echo $row['Callsign']?>"] ['state'] = 0;
    talkgroups_active["<?php echo $row['Callsign']?>"] ['name'] ="<?php echo $row['Callsign']?>"
    

    <?php }?>
   
   
}
var json_old;
var json new;

var dryrun_json  =0;


function compare_json(data)
{
	if(dryrun_json == 0)
	{
		json_old =data;
	}
	
	
	for(var k in data.nodes)
	{
		if(json_old.nodes[k] == data.nodes[K] )
		{
		
    		if (talkgroups_active[k] ['state'] == 0)
    		{
    			console.log("add_station "+k);
    		
    		
    		}
		
		} 
		
		
	
	}
	
	for(var k in data.nodes)
	{
		if(json_old.nodes[k] == data.nodes[K] )
		{
		
    		if (talkgroups_active[k] ['state'] == 1)
    		{
    			console.log("add_station "+k);
    		
    		
    		}
		
		} 
		
		
	
	}
	
	
	
	
	
	
	


}




