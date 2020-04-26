function old()
{
$.getJSON("repeater-info-SK2RIU.json", function( info ) {
    var tab = "";
    for (index = 0; index < info.rxlist.length; ++index) {
        var rx = info.rxlist[index];
        switch (info.rx[rx].type)
        {
        case "radio":
            tab = tab + '<tr id="'+rx+'">'+
                        '<td class="rx">'+info.rx[rx].name+'</td>'+
                        '<td class="sql"></td><td class="lvl"></td>'+
                        '<td class="bar"><div></div></td></tr>\r\n';
            break;

        case "virtual":
            tab = tab + '<tr id="'+rx+'">'+
                        '<td class="rx">'+info.rx[rx].name+'</td>'+
                        '<td class="sql"></td><td></td><td></td></tr>\r\n';
            break;
        }
    }

    $("#sigtab").replaceWith(tab);
    $("#callsign").replaceWith(info.callsign);

    var freq = info.output_freq / 1000000.00;
    var offs = (info.input_freq - info.output_freq) / 1000000.0;


    if (offs >= 0) {
        offs = '+'+offs;
    }

    $('#freq').replaceWith(' ('+freq.toFixed(4)+' MHz '+offs+')');

    var stream = info.uri.audiostream;
    if (stream) {
        $('#stream').html('<a href="'+stream+'" target="_blank">'+
            '<img src="/icons/sound1.png"></a>');
    }

    var jsonStream = new EventSource(info.uri.signalEventStream);
    jsonStream.onmessage = function (e) {
        var message = JSON.parse(e.data);

        if (message.event == 'Logic:transmit') {
            var tx = '';
            if (message.tx == 1) { tx = '<img src="/icons/ball.red.png">'; };
            $('#tx').html(tx);
        }

        if (message.event == 'Voter:sql_state') {
            for (index = 0; index < info.rxlist.length; ++index) {
                var rx = info.rxlist[index];
                var tri = 'tr#'+rx+' ';

                if (typeof message.rx[rx] == 'undefined') {
                    $(tri+'td.sql').text('undef');
                    $(tri+'td.lvl').text('');
                    $(tri+'td.bar div').width('0px');
                } else {
                    var sql = message.rx[rx].sql;
                    var lvl = message.rx[rx].lvl;
                    $(tri+'td.sql').text(sql);
                    $(tri+'td.lvl').text(lvl);
                    if (lvl < 0) { lvl = 0; }
                    if (lvl > 100) { lvl = 100; };
                    $(tri+'td.bar div').width(4*lvl+'px');
                    var color = '#808080';
                    if (sql == 'open') { color = 'yellow' };
                    if (sql == 'active') { color = 'red' };
                    $(tri+'td.bar div').css('background-color',color);
                }
            }
        }
    };
});
}


function stat_change(json)
{

 load_Recivers_html();



setTimeout(function(){
    //do what you need here

$.getJSON(""+json+"", function( info ) {
    var tab = "";

    for (index = 0; index < info.rxlist.length; ++index) {
        var rx = info.rxlist[index];
        switch (info.rx[rx].type)
        {
        case "radio":
            tab = tab + '<tr id="'+rx+'">'+
                        '<td class="rx">'+info.rx[rx].name+'</td>'+
                        '<td class="sql"></td><td class="lvl"></td>'+
                        '<td class="bar"><div></div></td></tr>\r\n';
            break;

        case "virtual":
            tab = tab + '<tr id="'+rx+'">'+
                        '<td class="rx">'+info.rx[rx].name+'</td>'+
                        '<td class="sql"></td><td></td><td></td></tr>\r\n';
            break;
        }
    }

    $("#sigtab").replaceWith(tab);
    $("#callsign").replaceWith(info.callsign);

    var freq = info.output_freq / 1000000.00;
    var offs = (info.input_freq - info.output_freq) / 1000000.0;


    if (offs >= 0) {
        offs = '+'+offs;
    }

    $('#freq').replaceWith(' ('+freq.toFixed(4)+' MHz '+offs+')');

    var stream = info.uri.audiostream;
    if (stream) {
        $('#stream').html('<a href="'+stream+'" target="_blank">'+
            '<img src="/icons/sound1.png"></a>');
    }

    var jsonStream = new EventSource(info.uri.signalEventStream);
    jsonStream.onmessage = function (e) {
        var message = JSON.parse(e.data);

        if (message.event == 'Logic:transmit') {
            var tx = '';
            if (message.tx == 1) { tx = '<img src="/icons/ball.red.png">'; };
            $('#tx').html(tx);
        }

        if (message.event == 'Voter:sql_state') {
            for (index = 0; index < info.rxlist.length; ++index) {
                var rx = info.rxlist[index];
                var tri = 'tr#'+rx+' ';

                if (typeof message.rx[rx] == 'undefined') {
                    $(tri+'td.sql').text('undef');
                    $(tri+'td.lvl').text('');
                    $(tri+'td.bar div').width('0px');
                } else {
                    var sql = message.rx[rx].sql;
                    var lvl = message.rx[rx].lvl;
                    $(tri+'td.sql').text(sql);
                    $(tri+'td.lvl').text(lvl);
                    if (lvl < 0) { lvl = 0; }
                    if (lvl > 100) { lvl = 100; };
                    $(tri+'td.bar div').width(4*lvl+'px');
                    var color = '#808080';
                    if (sql == 'open') { color = 'yellow' };
                    if (sql == 'active') { color = 'red' };
                    $(tri+'td.bar div').css('background-color',color);
                }
            }
        }
    };
});

}, 200);


}

function multi_stat_change(json,id)
{

 apeend_Recivers_html(id);



setTimeout(function(){
    //do what you need here

$.getJSON(""+json+"", function( info ) {
    var tab = "";

    for (index = 0; index < info.rxlist.length; ++index) {
        var rx = info.rxlist[index];
        switch (info.rx[rx].type)
        {
        case "radio":
            tab = tab + '<tr id="'+rx+'">'+
                        '<td class="rx">'+info.rx[rx].name+'</td>'+
                        '<td class="sql"></td><td class="lvl"></td>'+
                        '<td class="bar"><div></div></td></tr>\r\n';
            break;

        case "virtual":
            tab = tab + '<tr id="'+rx+'">'+
                        '<td class="rx">'+info.rx[rx].name+'</td>'+
                        '<td class="sql"></td><td></td><td></td></tr>\r\n';
            break;
        }
    }

    $("#sigtab"+id).replaceWith(tab);
    $("#callsign"+id).replaceWith(info.callsign);

    var freq = info.output_freq / 1000000.00;
    var offs = (info.input_freq - info.output_freq) / 1000000.0;


    if (offs >= 0) {
        offs = '+'+offs;
    }

    $('#freq'+id).replaceWith(' ('+freq.toFixed(4)+' MHz '+offs+')');

    var stream = info.uri.audiostream;
    if (stream) {
        $('#stream'+id).html('<a href="'+stream+'" target="_blank">'+
            '<img src="/icons/sound1.png"></a>');
    }

    var jsonStream = new EventSource(info.uri.signalEventStream);
    jsonStream.onmessage = function (e) {
        var message = JSON.parse(e.data);

        if (message.event == 'Logic:transmit') {
            var tx = '';
            if (message.tx == 1) { tx = '<img src="/icons/ball.red.png">'; };
            $('#tx'+id).html(tx);
        }

        if (message.event == 'Voter:sql_state') {
            for (index = 0; index < info.rxlist.length; ++index) {
                var rx = info.rxlist[index];
                var tri = 'tr#'+rx+' ';

                if (typeof message.rx[rx] == 'undefined') {
                    $(tri+'td.sql').text('undef');
                    $(tri+'td.lvl').text('');
                    $(tri+'td.bar div').width('0px');
                } else {
                    var sql = message.rx[rx].sql;
                    var lvl = message.rx[rx].lvl;
                    $(tri+'td.sql').text(sql);
                    $(tri+'td.lvl').text(lvl);
                    if (lvl < 0) { lvl = 0; }
                    if (lvl > 100) { lvl = 100; };
                    $(tri+'td.bar div').width(4*lvl+'px');
                    var color = '#808080';
                    if (sql == 'open') { color = 'yellow' };
                    if (sql == 'active') { color = 'red' };
                    $(tri+'td.bar div').css('background-color',color);
                }
            }
        }
    };
});

}, 200);


}

function Draw_bar(id, lat,lon)
{


	var canvas = document.createElement('canvas');
	var context = canvas.getContext('2d');
	canvas.setAttribute('width', 10);
	canvas.setAttribute('id', 'bar_'+id);
	canvas.setAttribute('height', 100);
	canvas.setAttribute('style', 'border:1px solid #000000;');
	
	 
	context.stroke(); 
	context.fillRect(1, 99 ,8, -0); 
	
	
	var panel = new ol.Overlay({
	    element: canvas,
	    stopEvent: false,
	    offset:[5,-200],
	    autoPan: false,
		anchorXUnits: 'fraction',
		anchorYUnits: 'fraction',
	    position: ol.proj.transform([lon,lat], 'EPSG:4326', 'EPSG:3857'),
	    positioning: 'top-right',
		autoPan: true
	   
	});
	map.addOverlay(panel);

}
function update_bar(id,value,sql)
{
	const canvas1 = document.getElementById('bar_'+id);
	var can =canvas1.getContext('2d');
	
	can.clearRect(1, 99 ,8, -100); 
	if(value>=0 && value <100)
		can.fillRect(1, 99 ,8, -1*value); 
	else if (value >=100)
		can.fillRect(1, 99 ,8, -100);

		
		if(sql == 'active')
	{
		 
		can.fillStyle ="#1932F7";

		
	}
	else if(sql == 'open')
	{
		can.fillStyle ="#E31013";

	}
	else
	{
		can.fillStyle ='black';
	}
	
	
}

var icon_save = new Array();
function canvas_icon(html,lat, lon,label,collor)
{

html=html.replace(" ","_");
var canvas = document.createElement('canvas');
var ctx = canvas.getContext('2d');
canvas.setAttribute('width', 60);
canvas.setAttribute('height', 100);
canvas.setAttribute('id', 'icon_'+html);


ctx.beginPath();
ctx.arc(30, 30, 25, 25, Math.PI * 2, true); // Outer circle

ctx.moveTo(5, 35);
ctx.lineTo(30, 80);
ctx.lineTo(55, 35);
ctx.lineWidth = 3;
ctx.stroke();
ctx.fillStyle =collor;
ctx.fill();
ctx.beginPath();
ctx.arc(30, 30, 10, 0, Math.PI * 2, true); // Outer circle
ctx.fillStyle ="#FFFFFF";
ctx.fill();
ctx.beginPath();
ctx.scale(-0.5, -0.5);

icon_save[html] = canvas.toDataURL();

iconFeature = new ol.Feature({
    geometry: new ol.geom.Point(new ol.proj.transform([lon,lat], 'EPSG:4326', 'EPSG:3857')),
});

var iconStyle = new ol.style.Style({
        image: new ol.style.Icon(({
        anchor: [0.5, 1.0],
        anchorXUnits: 'fraction',
        anchorYUnits: 'fraction',
        opacity: 0.9,
        src: icon_save[html],
		scale:0.5
        })),
        text: new ol.style.Text({
                     font: '12px helvetica,sans-serif',
                     text: label,
                     fontSize: 12,
					 scale:1.2,
                     fill: new ol.style.Fill({
                         color: '#000'
                     }),
                     stroke: new ol.style.Stroke({
                         color: '#fff',
                         width: 2
                     })
                  })
});

iconFeature.setStyle(iconStyle);
iconFeature.setId(html);
vectorSource.addFeature(iconFeature);

return vectorLayer;


}










