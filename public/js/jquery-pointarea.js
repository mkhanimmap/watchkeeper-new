//adapt form http://blog.sallarp.com/geojson-google-maps-editor.html
var allOverlays = new Array();
var map;
var drawingManager = null;
var gmarkers = [];
var infowindow;
var settingsItemsMap;
var geocoder;
var markers = [];
var drawingManagerModeOld = '';
var geo={};
//todo GeometryCollection
$(document).ready(function() {
    // Find the users current position.  Cache the location for 5 minutes, timeout after 6 seconds
    options={maximumAge: 500000, enableHighAccuracy:true, timeout: 6000};
    if (navigator.geolocation) {
        //Get the latitude and the longitude;
        function getClientLatLng(position) {
            geo.lat = position.coords.latitude;
            geo.lng = position.coords.longitude;
            initialize();
        }
        function error(){
            initialize();
        }
        navigator.geolocation.getCurrentPosition(getClientLatLng, error,options);
    } else {
        initialize();
    }
    function initialize() {
        $("#loadJsonButton").click(function() {
            loadGeoJSON();
        });

        $("#clearmap").click(function() {
            clearMap(allOverlays);
            clearAllOverlays(gmarkers);
        });

        //prevent enter to submit
        $('#id-field-search-location').keypress(function(event) {
            var code = event.keyCode || event.which;
            if (code  == 13) {
                event.preventDefault();
                return false;
            }
        });

        google.maps.Polygon.prototype.getBounds = function() {
            var bounds = new google.maps.LatLngBounds();
            var paths = this.getPaths();
            var path;
            for (var i = 0; i < paths.getLength(); i++) {
                path = paths.getAt(i);
                for (var j = 0; j < path.getLength(); j++) {
                    bounds.extend(path.getAt(j));
                }
            }
            return bounds;
        }

        infowindow = new google.maps.InfoWindow({size: new google.maps.Size(150,50)});
        geocoder = new google.maps.Geocoder();

        settingsItemsMap = {
            zoom: 12,
            center: new google.maps.LatLng(geo.lat, geo.lng),
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE
            },
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var input = document.getElementById('id-field-search-location');
        var searchBox = new google.maps.places.SearchBox(input);

        map = new google.maps.Map(document.getElementById('map-canvas'), settingsItemsMap );
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        service = new google.maps.places.PlacesService(map);
        var drawingManager = new google.maps.drawing.DrawingManager({
            rawingMode: google.maps.drawing.OverlayType.POLYGON,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [
                google.maps.drawing.OverlayType.MARKER,
                google.maps.drawing.OverlayType.POLYGON
                ]
            },
            polygonOptions: {
                editable: true,
                zIndex: 1
            }
        });

        drawingManager.setMap(map);
        loadGeoJSON();
        //overlaycomplete
        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
            if (drawingManagerModeOld == '') {
                drawingManagerModeOld = event.type
            }
            else if (drawingManagerModeOld != event.type) {
                drawingManagerModeOld = event.type
                clearMap(allOverlays);
                clearAllOverlays(gmarkers);
            }
            if (event.type === google.maps.drawing.OverlayType.POLYGON || event.type === google.maps.drawing.OverlayType.POLYLINE) {
                addMapOverlay(event.overlay);
            } else if (event.type === google.maps.drawing.OverlayType.MARKER) {
                allOverlays.push(event.overlay);
                var overlay = event.overlay;
                var position = new google.maps.LatLng(overlay.getPosition().k,overlay.getPosition().A);
                gmarkers.push(getmarker(map,position));
                writeGeojosn();
            }
        });
        //places_changed
        google.maps.event.addListener(searchBox, 'places_changed', function(event) {
            var places = searchBox.getPlaces();
            var bounds = new google.maps.LatLngBounds();
            for (var i = 0, place; place = places[i]; i++) {
                var place = places[i];
                createMarker(place);
                bounds.extend(place.geometry.location);
            }
            writeGeojosn();
            map.fitBounds(bounds);
        });
    } // end o initailise

    function writeGeojosn() {
        var coordinates = new Array();
        for (var k = 0; k < gmarkers.length; k++){
            var position = gmarkers[k].getPosition();
            var latLng = new Array(position.k, position.A);
            coordinates.push(new Array(latLng));
        }
        var geoJSON = { 'type': 'MultiPoint', 'coordinates' : coordinates };
        $( "#id-field-geoJSON" ).val(JSON.stringify(geoJSON, null, '\t'));
    }

    function addMapOverlay(overlay) {
        allOverlays.push(overlay);
        overlay.setMap(map);
        google.maps.event.addListener(overlay.getPath(), 'insert_at', function() {
            dumpGeoJSON(allOverlays);
        });

        google.maps.event.addListener(overlay.getPath(), 'remove_at', function() {
            dumpGeoJSON(allOverlays);
        });

        google.maps.event.addListener(overlay.getPath(), 'set_at', function() {
            dumpGeoJSON(allOverlays);
        });
        dumpGeoJSON(allOverlays);
    }

    function dumpGeoJSON(overlays) {
        var coordinates = new Array();
        for (var i = 0; i < overlays.length; i++) {
            var polygon = new Array();
            var vertices = overlays[i].getPath();
            for (var j = 0; j < vertices.length; j++) {
                var xy = vertices.getAt(j);
                var lngLat = new Array(xy.lng(), xy.lat());
                polygon.push(lngLat);
            }
            // Add the first polygon again to close the polygon.
            var xy = vertices.getAt(0);
            polygon.push(new Array(xy.lng(), xy.lat()));
            coordinates.push(new Array(polygon));
        }
        var geoJSON = { 'type': 'MultiPolygon', 'coordinates' : coordinates };
        $( "#id-field-geoJSON" ).val(JSON.stringify(geoJSON, null, '\t'));
    }

    function clearAllOverlays(overlays) {
        // Remove all existing overlays
        for (var i = 0; i < overlays.length; i++) {
            overlays[i].setMap(null);
            overlays[i] = null;
        }
        overlays.length = 0;
        overlays = [];
    } // end of clearAllOverlays

    function clearMap(overlays) {
        // Remove all existing overlays
        clearAllOverlays(overlays);
        var textarea = $( "#id-field-geoJSON" );
        textarea.val('');
    } // end of clearMap

    function getmarker(_map,_position,_icon) {
        _icon = (typeof icon !== 'undefined') ? _icon : null;
        var marker = new google.maps.Marker({
            map:_map,
            icon: _icon,
            position:_position,
            draggable:true
        });
        google.maps.event.addListener(marker, 'dragend', function() {
            map.setZoom(8);
            map.setCenter(marker.getPosition());
            writeGeojosn();
        });

        return marker;
    }


    // www.geocodezip.com/v3_GoogleEx_places-searchboxB.html
    function createMarker(place) {
        var placeLoc=place.geometry.location;
        if (place.icon) {
            var image = new google.maps.MarkerImage(
                place.icon, new google.maps.Size(71, 71),
                new google.maps.Point(0, 0), new google.maps.Point(17, 34),
                new google.maps.Size(25, 25));
        }
        else {
            var image = null;
        }
        var marker = getmarker(map,place.geometry.location,image);

        var request =  {
          reference: place.reference
        };

        google.maps.event.addListener(marker,'click',function(event) {
            service.getDetails(request,
                function(place, status) {
                    if (status == google.maps.places.PlacesServiceStatus.OK) {
                        var contentStr = '<h5>'+place.name+'</h5><p>'+place.formatted_address;
                        if (!!place.formatted_phone_number) contentStr += '<br>'+place.formatted_phone_number;
                        if (!!place.website) contentStr += '<br><a target="_blank" href="'+place.website+'">'+place.website+'</a>';
                        contentStr += '<br>'+place.types+'</p>';
                        infowindow.setContent(contentStr);
                        infowindow.open(map,marker);
                    }
                    else {
                        var contentStr = "<h5>No Result, status="+status+"</h5>";
                        infowindow.setContent(contentStr);
                        infowindow.open(map,marker);
                    }
                });
        });
        gmarkers.push(marker);
    } // end of createMarker

    function loadGeoJSON() {
        var textarea = $( "#id-field-geoJSON" );
        var geoJSON;
        clearAllOverlays(allOverlays);
        try
        {
            if ($.trim(textarea.val()) != "") {
                geoJSON = JSON.parse(textarea.val());
            }
            if (typeof geoJSON == "undefined") {
                return;
            }
        }
        catch(e) {
            alert('json parse failed: \n' + e);
            return;
        }
        if (!geoJSON.coordinates) {
            return;
        }

        if (geoJSON.type == 'MultiPolygon') {
            var coords = new Array();
            var overlay;
            for (i in geoJSON.coordinates) {
               var polyCoords = new Array();
               for (j in geoJSON.coordinates[i][0]) {
                   var coords = geoJSON.coordinates[i][0][j];
                   var latlng = new google.maps.LatLng(coords[1], coords[0]);
                   polyCoords.push(latlng);
                   coords.push(latlng);
               }
               overlay = new google.maps.Polygon({ map: map, paths: polyCoords, editable: true });
               addMapOverlay(overlay);
           }
           drawingManagerModeOld = 'polygon';
           fitAllPolygon(overlay);
       }
       else if ( geoJSON.type == 'MultiPoint' ) {
            var coords = new Array();
            for (i in geoJSON.coordinates) {
                if (geoJSON.coordinates[i].length > 0 && geoJSON.coordinates[i][0].length == 2) {
                    var coordinates = geoJSON.coordinates[i];
                    var latlng = new google.maps.LatLng(geoJSON.coordinates[i][0][0],geoJSON.coordinates[i][0][1]);
                    coords.push(latlng);
                    gmarkers.push(getmarker(map,latlng));
                }
            }
            drawingManagerModeOld = 'marker';
            fitAllMarker(coords);
        }
    } // end of loadGeoJSON
    function fitAllPolygon(polygon) {
        map.fitBounds(polygon.getBounds());
    }

    function fitAllMarker(latlng) {
        if ( latlng.length > 0 ) {
            var latlngbounds = new google.maps.LatLngBounds();
            for (var i = 0; i < latlng.length; i++) {
              latlngbounds.extend(latlng[i]);
          }
          map.fitBounds(latlngbounds);
        }
    }
}); // end of ready
