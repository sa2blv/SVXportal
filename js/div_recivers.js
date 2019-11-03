var filter_station = "";
function update_filter(value) {
	filter_station = value;
	$('#holder').html("");
	call_svxrefelktor();

}

var interval;
var totalSeconds = 0;
var current_talker = "";

function call_svxrefelktor() {
	
	$
			.getJSON(
					refelktor_address,
					function(data) {
						//console.log(data);
						if (filter_station != "") {

							for ( var k in data.nodes) {

								if (filter_station != k)
									delete data.nodes[k];
							}
						}

						for ( var k in data.nodes) {

							var text = " ";

							var image = '<img src="images/talking.gif" alt="talk" id="talking" width="25px">';
							
							if (document.getElementById('div' + k)) {
								if (data.nodes[k].isTalker == false) {
									// $('#holder').html('<div
									// id="div'+k+'">'+k+''+data.nodes[k].tg+'</div>');
									$('#div' + k + ' h1').removeClass(
											"bg-success");

								} else {
									// $('#row'+k+'').html('<td>'+k+'</td>'+'<td>'+data.nodes[k].tg+'</td>'+'<td
									// class="green_collor"
									// >YES'+image+'</td><td></td><td></td><td><label
									// id="minutes">00</label>:<label
									// id="seconds">00</label></td>');
									$('#div' + k + ' h1')
											.addClass("bg-success");
									write_log(k+" started taking on tg"+data.nodes[k].tg);
								}
							} else {
								write_log("Added qth "+k);
								$('#holder')
										.append(
												'<div id="div'
														+ k
														+ '"><h2>Signal values at '
														+ k
														+ ' ( '
														+ data.nodes[k].tg
														+ ' )</h2><table class="table table-striped  table-hover"  id="status-'
														+ k
														+ '"></table></div>');
								$('#status-' + k)
										.html(
												'<thead class="bg-info"><th class="w-25 p-3">Receiver</th><th></th><th>Bargraph</th><th>Signal</th><th>Frequency</th>Frequency<th></th></thead>');
							}

							// if qth is defined

							for ( var qth in data.nodes[k].qth) {
								for ( var qth1 in data.nodes[k].qth[qth].rx) {

									var qth_name = data.nodes[k].qth[qth].rx[qth1].name;
									var rx_active = data.nodes[k].qth[qth].rx[qth1].active;
									var rx_sql = data.nodes[k].qth[qth].rx[qth1].sql_open;
									var sql = data.nodes[k].qth[qth].name;
									var value = data.nodes[k].qth[qth].rx[qth1].siglev;
									var Freqvensy = data.nodes[k].qth[qth].rx[qth1].freq;
									var name_id = data.nodes[k].qth[qth].name
											+ qth1;
									name_id = name_id.trim()
									if (value == undefined)
										value = 0;

									var qth_html_add;
									var class_row = ""
									if (rx_active == true) {
										class_row = ""
									}

									if (document
											.getElementById('row' + name_id)) {
										
										qth_html_add = '<td> * ' + qth_name
												+ '</td><td colspan="2" id="td'
												+ k + '_' + qth_name
												+ '"><canvas id="bar_' + k
												+ '_' + qth_name
												+ '"></canvas></p> </td><td>'
												+ parseInt(value)
												+ '%</td><td>' + Freqvensy
												+ ' Mhz</td><td></td>';
										$('#row' + name_id).html(qth_html_add);
										$('#row' + name_id).addClass(
												"class_row");
									} else {
										qth_html_add = '<tr class="table-striped  '
												+ class_row
												+ ' table-borderless" id="row'
												+ name_id
												+ '"  ><td> * '
												+ qth_name
												+ '</td><td colspan="2" id="td'
												+ k
												+ '_'
												+ qth_name
												+ '"><canvas id="bar_'
												+ k
												+ '_'
												+ qth_name
												+ '"></canvas></p> </td><td>'
												+ parseInt(value)
												+ '%</td><td>'
												+ Freqvensy
												+ ' Mhz</td><td></td></tr>';

										$('#status-' + k).append(qth_html_add);
										$('#row' + qth_name).removeClass(
												"class_row");
									}

									var canvas = document.getElementById('bar_'
											+ k + '_' + qth_name);
									var context = canvas.getContext('2d');
									width = 0.2 * window.innerWidth
									var value_scale = width / 100;
									canvas.setAttribute('width', width);

									canvas.setAttribute('height', 10);
									canvas.setAttribute('style',
											'border:1px solid #000000;');

									context.stroke();
									context.fillRect(1, 1, -0, 1);

									if (rx_active == true && rx_sql == true) {
										write_log("Reciver "+qth_name+" set to active");
										context.fillStyle = "#1932F7";

									} else if (rx_sql == true) {
										write_log("Reciver "+qth_name+" set to SQl open");
										context.fillStyle = "#E31013";

									} else {
										context.fillStyle = 'black';
									}

									if (value >= 0 && value < 100)
										context.fillRect(1, 1,
												(value_scale * value) - 3, 8);
									else if (value >= 100)
										context.fillRect(1, 1, width - 3, 8);

								}
							}

						}

						interval = setTimeout(call_svxrefelktor, 500);
					});

}

function setTime() {

}

function pad(val) {
	var valString = val + "";
	if (valString.length < 2) {
		return "0" + valString;
	} else {
		return valString;
	}
}
function load_reflector()
{
    call_svxrefelktor();
    $('#selects').html('<option value=""> -- All -- </option>');
    $.getJSON( refelktor_address, function( data ) {
    	


        
      for(var k in data.nodes){

            	$('#selects').append($('<option>', {
            	    value: k,
            	    text: k
            	}));
            }
        

    });	
}
function write_log(logtext)
{
	var d = new Date();
	var textstring ="";
	textstring = d.getFullYear()+"-"+ d.getMonth()+"-"+d.getDate()+"-"+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();
	textstring=textstring+="	"+logtext+"</br >";
	
	$( "#logdiv" ).prepend( textstring );
}




