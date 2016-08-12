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
  <!--Let browser know website is optimized for mobile-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link type="text/css" rel="stylesheet" href="resources/css/stylesheet.css" />
  <link type="text/css" rel="stylesheet" href="resources/css/animate.css" />
</head>

<body>  
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

  <div id="main-map-container" style="height:100%; transition: all 0.4s linear;">
  </div>  

  <div id="main-catalog-container" style="height:100%;" class="hidden-dn">
  	<h2> Catalogo </h2>
  </div>  

  <!-- code -->
  <script type="text/javascript" src="vendors/jquery/js/jquery-3.1.0.min.js"></script>
  <script type="text/javascript" src="vendors/materialize/js/materialize.min.js"></script>
  <script type="text/javascript" src="vendors/leaflet/leaflet.js"></script>

  <script> 
   $(document).ready(function() { 

		$(".button-collapse").sideNav();

		var map = L.map('main-map-container').setView([22.59,-84], 6); 
		// Tiles - we using basemaps dark
		L.tileLayer('//{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png', {
		maxZoom: 18,
		attribution: 'Maps by CartoDB'
		}).addTo(map);     	 

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
</body>
</html>
