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
// default colors
var default_active_color = "00FF00";
var default_inactive_color = "FF0000";
var default_color = "FFFFFF";
var new_color ="dddddd";
var image = $('#innermap');
image.mapster({
        

	mapKey: 'data-key',
	fillOpacity: 1,
        //fillColor: default_color,  //normally never used
        fillColor: default_inactive_color,  //normally never used

        onClick: function (e) {


            if (e.key == 'NH') {
        	//logic for color change
		var opts = image.mapster('get_options', null, true);	
                var current_color = opts.render_select.fillColor;
		if (current_color == default_active_color){
			new_color = default_inactive_color;
		} else if (current_color == default_inactive_color){
				new_color = default_active_color;
				}else{
					alert ("ERROR Color status undefinied!!");
					}


            }

            image.mapster('set_options', {
                areas: [{
                    key: "NH",
                    fillColor: new_color                                                        
                    }]
                });

        },
        areas: [
            {
                key: "NH",
                fillColor: default_color
            }
            ]
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
                    <li><a href="">Emergency Stop!</a></li>
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
					<img src="map/train1.png" usemap="#train1" border="0" id="innermap">
					<map name="train1" id="train1_map">
						<area shape="circle" coords="100,100,10" href="#" data-key="NH">
						<area shape="circle" coords="200,100,10" href="#" data-key="NY">
					</map>

				</div>
			</div>
			<div class="panel panel-default"><a name="outer"></a>
				<div class="panel-heading">Outer Circuit</div>
				<div class="panel-body">
					<img src="map/train2.png" usemap="#train2" border="0" id="outermap">
					<map name="train2">
					<area shape="polygon" coords="19,44,45,11,87,37,82,76,49,98" href="http://www.trees.com/save.html">
					<area shape="rect" coords="128,132,241,179" href="http://www.trees.com/furniture.html">
					<area shape="circle" coords="68,211,35" href="http://www.trees.com/plantations.html">
					</map>

				</div>
			</div>
	</div>
</div>
</div>


</body>
</html>
