<?php 
require_once("app-top.php") ; 

?>
<!DOCTYPE html>
<html>
<head>
  <!-- Google Icon Font-->
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">  
  <link type="text/css" rel="stylesheet" href="vendors/materialize/css/materialize.min.css"  media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="vendors/leaflet/leaflet.css"  media="screen,projection"/>
  <script type="text/javascript" src="vendors/vis.js/vis.min.js"></script>
  <link type="text/css" rel="stylesheet" href="vendors/vis.js/vis.min.css"/>

  <!--Let browser know website is optimized for mobile-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link type="text/css" rel="stylesheet" href="resources/css/stylesheet.css" />
  <link type="text/css" rel="stylesheet" href="resources/css/animate.css" />
</head>

<body>  
<div id="wrapper" > 
 <div id="header">
  <nav class="hide-on-small-only"> <!-- header --> 
    <div class="nav-wrapper grey darken-1">
      <a href="#" class="brand-logo">Vis-Sener</a>
    </div>
  </nav>  

  <nav>
    <div class="nav-wrapper grey">
      <div style="text-align:center;float: right; width:10%;">
      	<a href="#" data-activates="menu-side-visener" class="button-collapse" style="float:none;"><i class="material-icons">menu</i></a>
      </div>
      <form class="nav-search-box">
        <div class="input-field">
          <input id="search" type="search" required>
          <label for="search"><i class="material-icons">search</i></label>
          <i class="material-icons">close</i>
        </div>
      </form>

      <div class="nav-menu-options ">
      <!-- <ul class="tabs nav-tabs-menu right hide-on-med-and-down light-blue lighten-2">
      	<li class="tab white-text"><a href="#">Buscar</a></li>
        <li class="tab white-text"><a href="#">Catalogo</a></li>
        <li class="tab white-text"><a href="#">Mapa</a></li>              
      </ul>  -->
      <ul class="right hide-on-med-and-down "> <!-- light-blue lighten-2 -->
		<li><a href="#"><span class="hide-on-med-only">Buscar</span><i class="material-icons left">search</i></a></li>
        <li><a id="btn-show-catalog" href="#">Catalogo<i class="material-icons left">view_list</i></a></li>
        <li><a id="btn-show-map" href="#">Mapa<i class="material-icons left">language</i></a></li>         
      	<li><a class="dropdown-button" href="#!" data-activates="visener-layers">Capas<i class="material-icons right">arrow_drop_down</i></a></li>
      </ul>
      </div>

      <ul id="menu-side-visener" class="side-nav">
      	<li><a href="#">Busqueda avanzada</a></li>
        <li><a href="#">Catalogo</a></li>
        <li><a href="#">Mapa</a></li>        
        <li><a href="#">Capas</a></li>        
      </ul>     
	  <ul id='visener-layers' class='dropdown-content'>
		<li><a href="#!">one</a></li>
	    <li><a href="#!">two</a></li>	    
	    <li><a href="#!">three</a></li>
	  </ul>          

    </div>
  </nav>
 </div> 

  <div id="main-map-container">
  </div>  

  <div id="main-timeline-container">

  <div id="main-catalog-container" style="height:100%;" class="hidden-dn">
  	<h2> Catalogo </h2>
  </div>  

  <!-- code -->
  <script type="text/javascript" src="vendors/jquery/js/jquery-3.1.0.min.js"></script>
  <script type="text/javascript" src="vendors/materialize/js/materialize.min.js"></script>
  <script type="text/javascript" src="vendors/leaflet/leaflet-src.js"></script>  
  <script type="text/javascript" src="vendors/turf.js/turf.min.js"></script>  
  <script type="text/javascript" src="resources/js/ERDDAP_client.js"></script>
  <script type="text/javascript" src="resources/js/modelcode.js"></script>

  <script> 
   $(document).ready(function() { 

		$(".button-collapse").sideNav();

		var endpoint = 'https://cic-pem.cicese.mx/erddap' ;  // The ERDDAP Endpoint 
		var proxy = 'http://localhost/phpProxy.php' ;        // This is needed on a local developing environment

		// ERDDAP Datasets 
		// NEMO_GOLFO12_dde9_130c_9625 Forecast Golfo12
		// MICROSTAR_2016_06_10 Trayectoria Boya microstrar 10




		var map = L.map('main-map-container').setView([22.59,-84], 6); 
		// Tiles - we using basemaps dark
		L.tileLayer('//{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: 'Maps by CartoDB'
		}).addTo(map);     	 

		// Vis js modules, Timeline
		var items = new vis.DataSet() ; 
		var options = {   
			moment: function(date) {
			    return vis.moment(date).utc(); }} ;
		var timeline = new vis.Timeline( $("#main-timeline-container").get(0) , items, options );




		var testwmsLayer ;
		dstgrid = new ERDDAP_Dataset(endpoint,proxy,'NEMO_GOLFO12_dde9_130c_9625', function(status) {

			if (status == 'Ok') {
				
				tc = dstgrid.getTimeCoverage() ;
				console.log('Grid Dataset loaded  : '  + JSON.stringify(tc)) ;
				items.add([{
					 id      : 'NEMO_GOLFO12_dde9_130c_9625' ,
					 content : dstgrid.getTitle() , 
					 start   : tc['start'] , 
					 end     : tc['end']     }]) ; 
				timeline.setItems(items) ;
				timeline.addCustomTime ( tc['start'] ) ; 

			    // Test adding a WMS layer 
			    testwmsLayer = L.tileLayer.wms('https://cic-pem.cicese.mx/erddap/wms/NEMO_GOLFO12_dde9_130c_9625/request?', {
			      layers: 'NEMO_GOLFO12_dde9_130c_9625:vosaline',
			      format: 'image/png',
			      transparent: true,
			      uppercase : true ,
			      crs : L.CRS.EPSG4326,  // Requerido 
			      ELEVATION : 2.0,       // Requerido
			      TIME: tc['start']  
			    }).addTo(map);		


			    
			}
		}) ;

		var geojTray ;
		dsttray = new ERDDAP_Dataset(endpoint,proxy,'MICROSTAR_2016_06_10', function(status) {

			if (status == 'Ok')	 {
				console.log('Trayectory Dataset loaded') ;
				tc = dsttray.getTimeCoverage() ; 
				items.add([{
					 id      : 'MICROSTAR_2016_06_10' ,
					 content : dsttray.getTitle() , 
					 start   : tc['start'] , 
					 end     : tc['end']     }]) ; 
				timeline.setItems(items) ;		

				// Load	data test
				urlr = 'http://localhost/phpProxy.php/erddap/tabledap/MICROSTAR_2016_06_10.geoJson?latitude,longitude&time>=' + tc['start'] + '&time<=' + timeline.getCustomTime().toISOString() ;
				$.getJSON(urlr , function(data){

					//console.log(data) ;
					linear = [] ;
					while (data['coordinates'].length) linear.push( data['coordinates'].splice(0,2) ) 
					
					mls = turf.multiLineString( linear ) ;					
					geojTray = L.geoJson(mls).addTo(map) ;

				})  ;
			}

		}) ;

		setTimeout(function() {
			timeline.on('timechanged', function(id,time) { 
				console.log(id['time'].toISOString()) ;
				testwmsLayer.setParams({TIME : id['time'].toISOString()}) ; 
				// Load	data test
				urlr = 'http://localhost/phpProxy.php/erddap/tabledap/MICROSTAR_2016_06_10.geoJson?latitude,longitude&time>=' + dsttray.getTimeCoverage()['start'] + '&time<=' + timeline.getCustomTime().toISOString() ;
				$.getJSON(urlr , function(data){

					//console.log(data) ;
					linear = [] ;
					while (data['coordinates'].length ) linear.push( data['coordinates'].splice(0,2) ) ;						
					
					map.removeLayer(geojTray) ;
					mls = turf.multiLineString( linear ) ;					
					geojTray = L.geoJson(mls).addTo(map) ;

				})  ;				
			}) ;			
		}, 7000) ;


		// Vis js timeline
	    // Create a DataSet (allows two way data-binding)
		//var items = new vis.DataSet([
		//	{id: 1, content: 'Forecast Salinidad', start: '2016-08-16T00:00:00Z', end : '2016-08-22T00:00:00Z'},
		//  {id: 2, content: 'Trayectoria Boya #', start: '2016-06-01T00:00:00Z', end: '2016-08-18T00:00:00Z'}
		//]);

		// Configuration for the Timeline
		//var options = {};
     	// Create a Timeline
		//var timeline = new vis.Timeline( $("#main-timeline-container").get(0) , items, options);		
		//timeline.addCustomTime ( '2016-08-16T00:00:00Z') ; 



		var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';	
		$("#btn-show-catalog").click(function(){
		    $("#main-map-container").addClass('animated slideOutRight');	    
		    $("#main-catalog-container").removeClass('hidden-dn').addClass('animated slideInLeft') ;
		    $("#main-map-container").one(animationEnd , function(e) {		      	
		      $("#main-map-container").addClass('hidden-dn').removeClass('animated slideOutRight');
		    });			

		}) ;
		$("#btn-show-map").click(function(){
		    $("#main-catalog-container").addClass('animated slideOutLeft');	    
		    $("#main-catalog-container").one(animationEnd , function(e) {
		      $("#main-map-container").removeClass('hidden-dn') ;	
		      $("#main-catalog-container").addClass('hidden-dn').removeClass('animated slideOutLeft');
		    });			

		}) ;		

   }) ;
   
  </script>
</div> <!-- wrapper -->   
</body>
</html>
