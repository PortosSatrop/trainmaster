<?php 

define('__ROOT__', dirname(__FILE__)); 
require_once(__ROOT__.'/basic.php'); 
?> 
<html>
<head>
<title>Train Master</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>
    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
<!-- JavaScripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php print_r($configv["home"]);?>/scripts/jquery.imagemapster.js"></script>
<!--<script type="text/javascript" src="<?php print_r($configv["home"]);?>/scripts/map.js"></script>-->
<script type="text/javascript">



$(document).ready(function ()
{

/* USING AJAX
function Notused_toggleDevice(device){
	var info = '{"device":"'+ device + '"}';
	$.ajax({ 
		type: "GET",
		dataType: "json",
		data: { method: "hello", format: "json" },
		url: "service.php",
		success: function(info){        
		 }

	}).done(function() {
		info = JSON.stringify(info);
		return info;	
	});

}
*/

function sendMessage (data){
	var xhttp;

	if (window.XMLHttpRequest) {
		xhttp = new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	data = encodeURI(data + '&format=json');
	xhttp.open("GET", data , false); //false to make it asynch
	xhttp.send();
	return xhttp.responseText;

}

function toggleDevice(cat, device){
	var info = '{"device":"'+ device + '","category":"'+ cat + '"}';
	var method = 'toggle';
	var data = "service.php?method=" + method + "&info=" + info;   
	return sendMessage(data);
}

function allStop(){
	var method = 'allstop';
	var data = 'service.php?method=' + method;   
	alert(sendMessage(data));
}

function allStart(){
	var method = 'allstart';
	var data = 'service.php?method=' + method;   
	alert(sendMessage(data));
}

function innerSize(size){
//alert ("NO FUNCIONA");
	var w = $('#innermap').width();
	w = w*size;
	$('#innermap').mapster('resize',w);
}

//Capture the EMERGENCY STOP and the ALL START
$('#Lallstop').click(function(){ allStop(); return false; });
$('#Lallstart').click(function(){ allStart(); return false; });

//Change maps size dynamically
$('#innerDecrease').click(function(){ innerSize(0.9); return false; });
$('#innerIncrease').click(function(){ innerSize(1.1); return false; });

// default colors
var default_active_color = "00FF00";
var default_inactive_color = "FF0000";
var default_color = "FFFFFF";
var image = $('#innermap');
image.mapster({
        

	mapKey: 'data-key',
	fillOpacity: 1,
        fillColor: default_color,  //normally never used
        //fillColor: default_inactive_color,  //normally never used
	
	render_select: {
		fillColor: default_active_color                                                        
        },

        onClick: function (e) {
		var rel = e.key;
		var v = rel.split("-");
		var cat;
		if (v[0] == "P"){
			cat="power";
		}
		if (v[0] == "S"){
			cat="switch";
		}
		var resp = toggleDevice(cat,v[1]);
		//alert (resp);
	/* En caso que vuelva al modo que cambia el color 
            if (e.key == 'NH') {
        	//logic for status change
		var opts = image.mapster('get_options', null, true);	
                var isSelected = opts.render_select.selected;
		if (isSelected){
			alert ("Si estaba seleccionado, des-seleccionandolo..!");
		} else {
			alert ("No estaba seleccionado, seleccionandolo..!");
			}


            }
	*/

        },

});
});

</script>
<style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
	
	#fixed-top {
	    position: fixed;
	    z-index: 1;
	    margin: auto;
	    width: 100%;
	}

	#content-pos {
	position: relative;
	top: 4em;

	}
    </style>

</head>
<body>
<div id="fixed-top">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <!-- Branding Image -->
                <a class="navbar-brand" href="/index.php">
                    Train Master
                </a>
            </div>
            <div class="collapse navbar-collapse" id="spark-navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="<?php print_r($configv["home"]);?>/index.php">Home</a></li>
                    <li><a href="#inner">Inner Circuit</a></li>
                    <li><a href="#outer">Outer Circuit</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a id="Lallstart" href="#">All Start!</a></li>
                    <li><a id="Lallstop" href="#">Emergency Stop!</a></li>
                </ul>
 
             </div>
        </div>
    </nav>
</div>
<div id="content-pos">
<div class="container spark-screen">
	<div class="row">
			<div class="panel panel-default"><a name="inner"></a>
				<div class="panel-heading">Inner Circuit</div>
				<div class="panel-body">
					<a id="innerIncrease" href="#">Increase</a>
					<a id="innerDecrease" href="#">Decrease</a>
					<img width="<?php print_r($configv["mapwidth"])?>%" src="map/train1.png" usemap="#train1" border="0" id="innermap">
					<map name="train1" id="train1_map">
						<area shape="circle" coords="228,44,<?php print_r($configv["electric_light_width"])?>" href="#" data-key="P-REL01">
						<area shape="circle" coords="228,108,<?php print_r($configv["electric_light_width"])?>" href="#" data-key="P-REL02">
					</map>

				</div>
			</div>
			<div class="panel panel-default"><a name="outer"></a>
				<div class="panel-heading">Outer Circuit</div>
				<div class="panel-body">
					<img width="<?php print_r($configv["mapwidth"])?>%" src="map/train2.png" usemap="#train2" border="0" id="outermap">
					<map name="train2">
					</map>

				</div>
			</div>
	</div>
</div>
</div>


</body>
</html>
