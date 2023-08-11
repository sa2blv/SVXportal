<?php
?>
<script type="text/javascript">

$( document ).ready(function() {
	//get_tg('');
});
var last_heard_active = 0;
function get_active_last()
{
	if (last_heard_active == 1)
	{
		get_tg('');
	}
	return;
}
function activate_last_heard()
{
	last_heard_active =1;
	get_tg('');
	return;
}

old_get_tg_data ="";

function get_tg(tg)
{


	$.get( "last_talker_api.php", { TG: tg } )
	  .done(function( data ) 
    {
		  const myArr = JSON.parse(data);

		  if(old_get_tg_data != data)
		  {
			  old_get_tg_data = data;
    		  $("#last_heard_table > tbody").html("");
    
    		  for(var k in myArr)
    	      {
    
    			  var box = "<div style=\"border:2px solid black; width: 25px; height :25px;  background-color:"+myArr[k].TG_collor+" \"></div>";
    			  var html_str = "<tr><td class=\"text-nowrap\" >"+myArr[k].Node+"</td><td>"+myArr[k].Location+"</td><td>"+box+"</td><td style=\"cursor: pointer;\" onclick=\"open_tg_window('"+myArr[k].TG+"')\">"+myArr[k].TG+"</td><td>"+myArr[k].TG_Name+"</td><td class='text-nowrap'>"+myArr[k].Time+"</td><td>"+myArr[k].Duration+"</td></tr>";
    			  $("#last_heard_table > tbody").append(html_str);
    			  
    		  }			  
		  }

		  

<?php
/*
 *
 *
 * $previus_talker = $row["Callsign"];
 * $json_data[$val]["Node"] = $row["Callsign"];
 * $json_data[$val]["Time"] = $row["Time"];
 * $json_data[$val]["TG"] = $row["Talkgroup"];
 * $json_data[$val]["Duration"] = $row["Talktime"];
 * $json_data[$val]["Location"] = $station_array["Location"];
 * $json_data[$val]["TG_collor"] = $tg_array["Collor"];
 * $json_data[$val]["TG_Name"] = $tg_array["TXT"];
 *
 */

?>
		  	


			    
	  });
	
}



</script>




    
    
    <div class="card shadow mb-4">
         
                
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between navbar-dark   text-white bg-dark">
                  <h6 class="m-0 font-weight-bold text-white"><?php echo _('Last heard')?></h6>
                  <div class="dropdown no-arrow ">
                    <a class="dropdown-toggle text-white" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400 "></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink" style="">
                      <div class="dropdown-header">Action menu</div>


                    </div>
                  </div>
                </div>
                
                
                
                
                <div class="">
                  <div class="card-body"> 
       
    
        
    
    		<table id="last_heard_table" class="table table-striped table-sm">
    			<thead class="dash_header">
    				<tr>
    					<th><?php echo _('Node')?></th>
    					<th><?php echo _('Location')?></th>
    					<th>&nbsp;</th>
    					<th><?php echo _('TG')?></th>
    					<th><?php echo _('TG Name')?></th>
    					<th><?php echo _('Time')?></th>
    					<th><?php echo _('Duration')?></th>
    
    				</tr>
    			</thead>
    			<tbody>
    
    			</tbody>
    
    
    		</table>

    
    
    

         </div>
              </div>
    

					</div>
					
					
					

    
    





