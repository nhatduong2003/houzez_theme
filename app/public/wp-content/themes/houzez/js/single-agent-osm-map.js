/*
* Show map on single property 
*/
jQuery( function($) {
	'use strict';

    var is_mapbox = houzez_vars.is_mapbox;
    var api_mapbox = houzez_vars.api_mapbox;

	if($('#houzez-agent-sidebar-map').length <= 0) {
        return;
    }
	var houzezMap;
	var mapBounds;
    var streetCount = 0;
    var mapZoom = 15;
    var panorama = null;
    var google_map_style = '';
    var showCircle = false;
	var closeIcon = "";
    var map_pin_type = 'marker';
	var infoWindowPlac = "";
	var markerPricePins = 'no';
	var mapType = 'roadmap';
    var propertyMarker;

    var agent_lat = $('#houzez-agent-sidebar-map').data('lat');
    var agent_lng = $('#houzez-agent-sidebar-map').data('lng');

    if ( agent_lat != "" && agent_lng != "" ) {
        
        if(is_mapbox == 'mapbox' && api_mapbox != '') {

            var tileLayer = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token='+api_mapbox, {
                attribution: '© <a href="https://www.mapbox.com/about/maps/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> <strong><a href="https://www.mapbox.com/map-feedback/" target="_blank">Improve this map</a></strong>',
                tileSize: 512,
                maxZoom: 18,
                zoomOffset: -1,
                id: 'mapbox/streets-v11',
                accessToken: 'YOUR_MAPBOX_ACCESS_TOKEN'
            });

        } else {
            var tileLayer = L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution : '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            } );
        }

        var mapCenter = L.latLng( agent_lat, agent_lng );

        var mapDragging = true;
        var mapOptions = {
            dragging: !L.Browser.mobile,
            center: mapCenter,
            zoom: mapZoom,
            zoomControl: true,
            tap: false,
        };

        houzezMap = L.map( 'houzez-agent-sidebar-map', mapOptions );
        houzezMap.scrollWheelZoom.disable();
        houzezMap.addLayer( tileLayer );


    } // End lat and lng if 

} );