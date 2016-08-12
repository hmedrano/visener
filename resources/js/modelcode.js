
/*
 *
 *
 * Requiere ERDDAP-client
 */


function VisLayer() {
	this.name      = "" ;
	this.opacity   = 1  ;	
	this.type	   = "" ;
}

VisLayer.prototype.draw = function() {
	// do the drawing in the UI map
}

VisLayer.prototype.layerInfo = function() {
	return {name: this.name} ;
}


/*
 *
 *
 */ 
function ModelLayer() {
	VisLayer.call(this) ;
 	// The wms source
	this.source    = "" ; 
	this.units	   = "" ;	
}

ModelLayer.prototype = Object.create(VisLayer.prototype) ;
ModelLayer.constructor = ModelLayer ;


/*
 *
 * 
 */
function StationatedBuoy(datasetID, callback) {
	VisLayer.call(this) ;
	// The tabledap source
	this.source = datasetID ; 	
	// Load the data set metadata to the object
	this.dataset = new ERDDAP_Dataset(endpoint,proxy,datasetID, function(status) {
		if (status){
			name = dataset.getTitle() ; 
			type = dataset.getType() ;
		}
		// after everything done
		callback() ; 
	}) ;
}

StationatedBuoy.prototype = Object.create(VisLayer.prototype) ; 
StationatedBuoy.constructor = StationatedBuoy ; 







 