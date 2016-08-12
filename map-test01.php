<?php 
require_once("app-top.php") ; 

?>
<!DOCTYPE html>
<html>
<head>
  <link type="text/css" rel="stylesheet" href="vendors/leaflet/leaflet.css"  media="screen,projection"/>
  <!--Let browser know website is optimized for mobile-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link type="text/css" rel="stylesheet" href="resources/css/stylesheet.css" />
  <link type="text/css" rel="stylesheet" href="resources/css/animate.css" />
</head>

<body style="overflow:hidden;margin:0;">  

  <div id="main-map-container" style="height:100%;">
  </div>  

  <!-- code -->
  <script type="text/javascript" src="vendors/jquery/js/jquery-3.1.0.min.js"></script>
  <script type="text/javascript" src="vendors/leaflet/leaflet.js"></script>
  <script type="text/javascript" src="resources/js/ERDDAP_client.js"></script>
  <script type="text/javascript" src="resources/js/modelcode.js"></script>
  

  <script> 
   $(document).ready(function() { 

		var endpoint = 'https://cic-pem.cicese.mx/erddap' ;  // The ERDDAP Endpoint 
		var proxy = 'http://localhost/phpProxy.php' ;        // This is needed on a local developing environment

		var map = L.map('main-map-container').setView([22.59,-84], 6); 
		// Tiles - we using basemaps dark
		L.tileLayer('//{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png', {
		maxZoom: 18,
		attribution: 'Maps by CartoDB'
		}).addTo(map);     

		// Iniciar layers
		// tampicobuoy = new StationatedBuoy() ;

		// Metodo onredraw, para redibujar las capas personalizadas.
		map.on('movestart',       function (e) { console.log('< movestart'); });
		map.on('moveend',         function (e) { console.log('> moveend'); });
		map.on('dragstart',       function (e) { console.log('    [ dragstart'); });
		map.on('dragend',         function (e) { console.log('    ] dragend'); });		
		map.on('zoomstart',       function (e) { console.log('    ( zoomstart'); });
		map.on('zoomend',         function (e) { console.log('    ) zoomend'); });
		map.on('viewreset',       function (e) { 
			console.log('      viewreset'); 
		});
		map.on('autopanstart',    function (e) { console.log('      autopanstart'); }) ;



		$(window).on("resize", function() {
		    //$("#main-map-container").height( $(window).height() * 0.96 ).width($(window).width() * 0.99 );
		    //map.invalidateSize();
		}).trigger("resize"); 

   }) ;
   
  </script>
</body>
</html>
