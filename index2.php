<?php 

define('__ROOT__', dirname(__FILE__)); 
require_once(__ROOT__.'/basic.php'); 

?> 
<html>
<head>
<title>Train Master</title>
<!-- JavaScripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php print_r($configv["home"]);?>/scripts/jquery.imagemapster.js"></script>
<script type="text/javascript">

$(document).ready(function ()
{
    $('#innermap').mapster({
        fillColor: 'FFFF00',
        fillOpacity: 1,
	mapKey: 'data-key'
    }).mapster('set',true,'NH');
});
</script>

</head>
<body>
<div id="content-pos">
<div class="container spark-screen">
	<div class="row">
			<div class="panel panel-default"><a name="inner"></a>
				<div class="panel-heading">Inner Circuit</div>
				<div class="panel-body">
					<img src="map/train1.png" usemap="#train1" border="0" id="innermap">
					<map name="train1">
						<area shape="circle" coords="100,100,50" href="#" data-key="NH">
					</map>

				</div>
			</div>
	</div>
</div>
</div>


</body>
</html>
