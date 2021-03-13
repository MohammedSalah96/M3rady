 var Maps = function () {

    var marker, infowindow;

    var init = function () {
        infowindow = new google.maps.InfoWindow();
        initMap();
     };

  
      
     function initMap() {
       
        var map = new google.maps.Map(document.getElementById('map'), {});

         var lat = $('#lat').val(), lng = $('#lng').val(), latlng;
         if (!lat && !lng || lat == 0 && lng == 0) {
             if (navigator.geolocation) {
                 navigator.geolocation.getCurrentPosition(function (position) {
                     var latitude = position.coords.latitude;
                     var longitude = position.coords.longitude;
                     latlng = new google.maps.LatLng(latitude, longitude);
                      geocode(map, latlng);
                });
             }else{
                latlng = new google.maps.LatLng('24.7136', '46.6753');
                 geocode(map, latlng);
             }
             
         } else {
            latlng = new google.maps.LatLng(lat, lng);
            geocode(map, latlng);
        }
       

        


         var input = document.getElementById('searchInput');
         map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

         var autocomplete = new google.maps.places.Autocomplete(input);
         autocomplete.bindTo('bounds', map);

         marker = new google.maps.Marker({
            map: map,
            anchorPoint: new google.maps.Point(0, -29),
            draggable: false
         });

         google.maps.event.addListener(map, 'click', function (event) {
            marker.setMap(null);
            geocode(map, event.latLng);
         });

         autocomplete.addListener('place_changed', function () {

             infowindow.close();
             marker.setVisible(false);
             var place = autocomplete.getPlace();
             if (!place.geometry) {
                 window.alert("Autocomplete's returned place contains no geometry");
                 return;
             }

             // If the place has a geometry, then present it on a map.
             if (place.geometry.viewport) {
                 map.fitBounds(place.geometry.viewport);
             } else {
                 map.setCenter(place.geometry.location);
                 map.setZoom(17);
             }

             marker.setPosition(place.geometry.location);
             marker.setVisible(true);

             var address = '';
             if (place.address_components) {
                 address = [
                     (place.address_components[0] && place.address_components[0].short_name || ''),
                     (place.address_components[1] && place.address_components[1].short_name || ''),
                     (place.address_components[2] && place.address_components[2].short_name || '')
                 ].join(' ');
             }

             infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
             infowindow.open(map, marker);

            $('#lat').val(place.geometry.location.lat());
            $('#lng').val(place.geometry.location.lng());
         });
     }

     var geocode = function (map, latlng) {
         geocoder = new google.maps.Geocoder();
         geocoder.geocode({
             'location': latlng
         }, function (results, status) {
             if (status === 'OK') {
                 if (results[0]) {
                     map.setZoom(15);
                     map.setCenter(latlng);
                     createMarker(map, latlng, results[0].formatted_address);
                     $('#lat').val(latlng.lat());
                     $('#lng').val(latlng.lng());
                 } else {
                     window.alert('No results found');
                 }
             } else {
                 window.alert('Geocoder failed due to: ' + status);
             }
         });
     }

     var createMarker = function (map, latlng, content) {
         var markerOptions = {
            position: latlng,
            map: map,
            draggable: false
         }
         marker = new google.maps.Marker(markerOptions);
         if (content !== null) {
             infowindow.close();
             addInfoWindow(marker, content, latlng);
         }
         return marker;
     }

     var addInfoWindow = function (marker, content, latlng) {
        infowindow.setContent(content);
        infowindow.setPosition(latlng);
        infowindow.open(map, marker);
     }

    return {
         init: function () {
             init();
         }
    }
}();
 
jQuery(document).ready(function () {
    Maps.init();
});
