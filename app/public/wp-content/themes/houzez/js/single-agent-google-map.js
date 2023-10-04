/*
* Show map on single property 
*/
jQuery( function($) {
	'use strict';


	if($('#houzez-agent-sidebar-map').length <= 0) {
        return;
    }
	var houzezMap;
	var mapBounds;
    var streetCount = 0;
    var show_map = 0;
    var mapZoom = 15;
    var panorama = null;
    var google_map_style = '';
    var showCircle = false;
	var closeIcon = "";
    var map_pin_type = 'marker';
	var infoWindowPlac = "";
	var markerPricePins = 'no';
	var mapType = 'roadmap';

    var agent_lat = $('#houzez-agent-sidebar-map').data('lat');
    var agent_lng = $('#houzez-agent-sidebar-map').data('lng');
     
    if( agent_lat != "" && agent_lng != "" ) {

        var agentLatLng = new google.maps.LatLng( agent_lat, agent_lng );
    	var mapOptions = {
            center: agentLatLng,
    		zoom : mapZoom,
            disableDefaultUI: true,
            scrollwheel : false
        };
        
        var panoramaOptions = {
            position: agentLatLng,
            pov: {
                heading: 34,
                pitch: 10
            }
        };

        houzezMap = new google.maps.Map( document.getElementById( "houzez-agent-sidebar-map" ), mapOptions );

        switch (mapType) {
    		case 'hybrid':
    			mapOptions.mapTypeId = google.maps.MapTypeId.HYBRID;
    			break;
    		case 'terrain':
    			mapOptions.mapTypeId = google.maps.MapTypeId.TERRAIN;
    			break;
    		case 'satellite':
    			mapOptions.mapTypeId = google.maps.MapTypeId.SATELLITE;
    			break;
    		default:
    			mapOptions.mapTypeId = google.maps.MapTypeId.ROADMAP;
    	}
    }

} );