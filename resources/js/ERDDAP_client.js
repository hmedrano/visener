// 
// Javascript ERDDAP client
// Requerimientos turf.js 
//
// Por Favio Medrano
// 

/* Si es necesario trabajar localmente, usar un proxy (ver phpProxy.php)
 * El proxy recibe como parametro la ruta, por lo tanto con la expresion
 * quitamos el dominio de la variable "ep" Ej. ep='http://cic-pem.cicese.mx/erddap' retorna "proxy"+'erddap'
 */ 
function build_endpoint_url(ep, proxy) {
	return proxy ? proxy + ep.replace(/^.*\/\/[^\/]+/, '') : ep ; 
}

/*
 * In construction.... 
 * 
 */
var ERRDAP_Variable = function(endpoint_url, proxy, datasetID, variableName, tableMeta) {
	var ID = datasetID ; 
	var ENDPOINT = endpoint_url ;  
	var PROXY = (typeof proxy !== 'undefined') ? proxy : null ; 
	var METADATA = tableMeta ; 	
	var OUTFORMAT = '.json' ; 	

	/*
	 * filterByField - Funcion que filtra los resultados de una busqueda de ERDDAP 
	 *				   que cumplan con el campo o columna "prop" == "valor"  y regresa esos 
	 *				   datos en el formato original, para filtar de nuevo si es necesario.	 
	 */
	function filterByField(prop, valor, tablaMeta) {
		if (tablaMeta) { 
			colnames = tablaMeta['columnNames'] ;
			pidx = colnames.indexOf(prop) ; if (pidx <0 ) return null ;	
			rows = tablaMeta['rows'] ;

			filtrado = { 'columnNames' : colnames , 'rows' : [] } ; 
			for (var r=0 ; r<rows.length ; r++) {
				if (rows[r][pidx] == valor) {
					filtrado['rows'].push(rows[r]) ; 
				}
			}
			return filtrado ; 			
		}
		return null ; 
	}	
	/*
	 * getField - Funcion recorta del formato de resultados ERDDAP, solo una columna o
	 *            campo "field"  y regresa ese arreglo o elemento.	
	 */
	function getField(field, tablaMeta) {
		if (tablaMeta) {			
			colnames = tablaMeta['columnNames'] ; 
			pidx = colnames.indexOf(field) ; if (pidx <0 ) return null ;
			rows = tablaMeta['rows'] ; 			
			if (rows.length == 1) {
				return rows[0][pidx] ;
			} else {
				results = [] ;
				for (var r=0 ; r<rows.length ; r++) {
					results.push(rows[r][pidx]) ;
				}
				return results ;
			}
		}
	}

	function getData() {
		endpoint = build_endpoint_url (ENDPOINT, PROXY) ; 

	}	

	metodos = {} ; 
	return metodos ;

} ;


/*
 * ERDDAP_Dataset  - Modulo para consultar metadatos de un dataset
 * 
 * Requiere jquery para solicitudes Ajax
 */
var ERDDAP_Dataset = function(endpoint_url, proxy, datasetID, callback) { 
	var ID = datasetID ; 
	var ENDPOINT = endpoint_url ;  
	var PROXY = (typeof proxy !== 'undefined') ? proxy : null ; 
	var METADATA = null ; 	
	var STATUS = '' ; 
	var OUTFORMAT = '.json' ; 

	function requestMeta(callback) {
		endpoint = build_endpoint_url (ENDPOINT, PROXY) ; 
		endpoint = endpoint + '/info/' + ID + '/index' + OUTFORMAT ; 

		var jqxhr = $.getJSON(endpoint, function(data) { 
			if (data) {
				METADATA = data['table'] ; STATUS = 'Ok' ;
			} else {
				METADATA = null ; STATUS = 'No results' ;
			} 
			if (callback) {
				callback(STATUS) ; 
			}
		}) ;
	}

	/*
	 * filterByField - Funcion que filtra los resultados de una busqueda de ERDDAP 
	 *				   que cumplan con el campo o columna "prop" == "valor"  y regresa esos 
	 *				   datos en el formato original, para filtar de nuevo si es necesario.	 
	 */
	function filterByField(prop, valor, tablaMeta) {
		if (tablaMeta) { 
			colnames = tablaMeta['columnNames'] ;
			pidx = colnames.indexOf(prop) ; if (pidx <0) return null ;	
			rows = tablaMeta['rows'] ;

			filtrado = { 'columnNames' : colnames , 'rows' : [] } ; 
			for (var r=0 ; r<rows.length ; r++) {
				if (rows[r][pidx] == valor) {
					filtrado['rows'].push(rows[r]) ; 
				}
			}
			return filtrado ; 			
		}
		return null ; 
	}	
	/*
	 * getField - Funcion recorta del formato de resultados ERDDAP, solo una columna o
	 *            campo "field"  y regresa ese arreglo o elemento.	
	 */
	function getField(field, tablaMeta) {
		if (tablaMeta) {			
			colnames = tablaMeta['columnNames'] ; 
			pidx = colnames.indexOf(field) ; if (pidx <0 ) return null ;
			rows = tablaMeta['rows'] ; 			
			if (rows.length == 1) {
				return rows[0][pidx] ;
			} else {
				results = [] ;
				for (var r=0 ; r<rows.length ; r++) {
					results.push(rows[r][pidx]) ;
				}
				return results ;
			}
		}
	}

	/* Metodos para obtener atributos del dataset, usamos las funcion filterByfield y getField */

	function getGlobalAtt(attname) {
		if (METADATA) {
			globalAtts = filterByField('Variable Name','NC_GLOBAL', METADATA) ;			
			return getField('Value' , filterByField('Attribute Name',attname , globalAtts ) )  ; 		
		}
	}

	function getTitle() { return getGlobalAtt('title') ; } 
	function getType() { return getGlobalAtt('cdm_data_type') ; }

	function getSpatialCoverage() {
		if (METADATA) {		
			globalAtts = filterByField('Variable Name','NC_GLOBAL',METADATA) ; 	
			latmax = getField('Value' , filterByField('Attribute Name','geospatial_lat_max', globalAtts ) )  ; 
			latmin = getField('Value' , filterByField('Attribute Name','geospatial_lat_min', globalAtts ) )  ; 
			lonmax = getField('Value' , filterByField('Attribute Name','geospatial_lon_max', globalAtts ) )  ; 
			lonmin = getField('Value' , filterByField('Attribute Name','geospatial_lon_min', globalAtts ) )  ; 
			return {'lonmin' : lonmin , 'lonmax' : lonmax , 'latmin' : latmin , 'latmax' : latmax} ;
		}
	}

	function getTimeCoverage() {
		if (METADATA) {		
			globalAtts = filterByField('Variable Name','NC_GLOBAL',METADATA) ; 	
			start = getField('Value' , filterByField('Attribute Name','time_coverage_start', globalAtts ) )  ; 
			end   = getField('Value' , filterByField('Attribute Name','time_coverage_end', globalAtts ) )  ; 
			return {'start' : start , 'end' : end} ;
		}
	}

	function getVariableNames() {
		if (METADATA) {
			return getField('Variable Name' , filterByField('Row Type','variable', METADATA) );			
		}
	}

	function tabledapRequest() {

	}

	function getData(vars, constraint) {
		if (METADATA) {
			endpoint = build_endpoint_url (ENDPOINT, PROXY) ; 
			endpoint = endpoint + '/info/' + ID + '/index' + OUTFORMAT ; 			
		}

	}

	// Solicitar metadatos al momento de la creacion de esta funcion
	requestMeta(callback) ; 

	// Metodos retornados	
	metodos = { getField : getField, 
				filterByField : filterByField , 
				getType : getType ,
				getTitle : getTitle , 
				getSpatialCoverage : getSpatialCoverage ,
				getTimeCoverage : getTimeCoverage,
				getVariableNames : getVariableNames } ;

	return metodos ;
} ;


/* 
 * ERDDAP_Search - Modulo para realizar una busqueda avanzada en un servidor
 *				   ERDDAP          
 *
 * Argumentos validos.
 *  page             :
 *  itemsPerPage     :
 *  searchFor		 : Cadena a buscar en los datasets
 *	protocol         :
 *  cdm_data_type
 *	institution
 *	keywords
 *	long_name
 *	standard_name
 *	variableName
 *	maxLat
 *	minLat
 *	maxLon
 *	minLon
 *	minTime          : Formato fecha YYYY-MM-DD'T'HH:mm:ss'Z'   (UTC GMT/Zulu Time)
 *	maxTime          : Formato fecha YYYY-MM-DD'T'HH:mm:ss'Z'   (UTC GMT/Zulu Time)  - Ejemplo: 2016-01-01T12:00:00Z
 *
 * Requiere jquery para solicitudes ajax
 */

var ERDDAP_Search = function(endpoint_url, proxy) {

	if (typeof endpoint_url == 'undefined') {
		return null ; 
	}
	// Propiedades
	var OUTFORMAT = '.json' ;
	var ENDPOINT = endpoint_url  ; 
	var PROXY = (typeof proxy !== 'undefined') ? proxy : null ; 
	var RAW_RESULTS = null ; 
	var RESULTS = null ;
	var STATUS = '' ;

	function cleanResults() {
		RAW_RESULTS = null ;  RESULTS = null ; STATUS = '' ;		
	}

	/*
	 * request - Funcion que se encarga de hacer la peticion al servicio de busqueda avanzada de 
	 *			 ERDDAP, los resultados se envian a la funcion handleResults que solicita metadatos
	 *			 de cada uno de los resultados.
	 *			 Los resultados se almacenan en el arreglo RESULTS con objetos tipo ERRDAP_Dataset
	 */
	function request(args, callback) {
		// Construir el url con la solicitud
		endpoint = build_endpoint_url(ENDPOINT,PROXY) ; 
		endpoint += '/search/advanced' + OUTFORMAT + '?' ; 
		Object.keys(args).forEach(function(key) {
			endpoint = endpoint + '&' + key + '=' + args[key] ;
		}) ; 
		endpoint = encodeURI(endpoint) ;
		console.log(endpoint) ;

		cleanResults() ;
		var jqxhr = $.getJSON(endpoint, function(data) { 
			if (data) {
				RAW_RESULTS = data['table'] ; STATUS = 'Ok' ;
				handleResults(callback) ; 
			} else { 
				RAW_RESULTS = null ; STATUS = 'No results' ;
				callback(0) ;
			} 
		})
		.fail(function() { 
			RAW_RESULTS = null ; STATUS = 'No results' ;
			callback(0) ;
		}) ;
	}

	function handleResults(callback) {
		if (RAW_RESULTS) {
			colnames = RAW_RESULTS['columnNames'] ; 			
			ididx = colnames.indexOf('Dataset ID') ; 
			RESULTS = [] ; 
			var totalResults = RAW_RESULTS['rows'].length ; 
			for (var r=0; r<RAW_RESULTS['rows'].length ; r++)  {
				row = RAW_RESULTS['rows'][r] ; 
				var dataset = new ERDDAP_Dataset(ENDPOINT, PROXY , row[ididx], function(status) {
					if (status == 'Ok') {
						totalResults = totalResults - 1 ; 
						if (totalResults==0) {
							callback(RAW_RESULTS['rows'].length) ; 
						}											
					}
				}) ; 
				RESULTS.push(dataset) ; 					
			}
		}	

	}

	function getResults() {
		if (RESULTS) {
			return RESULTS ;
		}
	}

	// Metodos
	metodos = { request : request ,
				getResults : getResults } ;
	return metodos ; 
} ;