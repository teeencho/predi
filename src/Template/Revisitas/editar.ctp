<?php
  $this->Html->css('dashboard', ['block' => true]);
  $this->extend('/Layout/admin');
  $this->Html->css('bootstrap-switch.min', ['block' => true]);
  $this->Html->script('bootstrap-switch.min', ['block' => true]);
?>
<div class="row">
  <div class="col-lg-5 main">
    <h1 class="page-header">Revisita</h1>
    <?= $this->Form->create($revisita, ['class' => 'form-horizontal']) ?>
      <div class="form-group">
        <?= $this->Form->input('nombre', ['required' => true,'class' => 'form-control', 'label' => ['text' => 'Nombre']]) ?>
      </div>
      <div class="form-group">
        <?= $this->Form->input('direccion', ['required' => true,'class' => 'form-control', 'label' => ['text' => 'Direccion']]) ?>
      </div>
      <div class="form-group">
        <button id="showmap" type="button" class="btn btn-info" data-toggle="modal" data-target="#mapa">Mapa</button>
      </div>
      <div class="form-group">
        <?= $this->Form->textarea('comentarios', ['required' => true,'class' => 'form-control', 'placeholder' => 'Comentarios', 'label' => ['text' => 'Comentarios']]) ?>
      </div>
      <div class="form-group">
        <?= $this->Form->input('clase', ['class' => 'form-control','placeholder' => 'Clase', 'label' => ['text' => 'Clase'],
        'options' => ['0' => 'Interesado', '1' => 'Revisita', '2' => 'Estudio'], 'required' => true]) ?>
      </div>
      <?= $this->Form->hidden('fecha', ['id' => 'fecha', 'value' => $revisita->fecha]); ?>
      <div class="form-group">
        <?= $this->Form->button(__('Guardar'), ['class' => 'btn btn-lg btn-primary btn-block']); ?>
      </div>
    <?= $this->Form->end() ?>
  </div> <!-- col-lg-5 -->
</div> <!-- row -->

<div id="mapa" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Mapa</h4>
      </div>
      <div class="modal-body">
        <!DOCTYPE html>
          <html>
            <head>
              <style>
                 #map {
                  height: 400px;
                  width: 100%;
                 }
              </style>
            </head>
            <body>
              <div id="map"></div>
              <script>
                var map;
                var marker;
                var infoWindow;
                var messageWindow;
                var geolocation;

                function initMap() {
                  var bsas = {lat: -34.5847136, lng: -58.4435577};
                  map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 15,
                    center: bsas
                  });

                  infoWindow = new google.maps.InfoWindow({
                    content: document.getElementById('form')
                  });
                  messageWindow = new google.maps.InfoWindow({
                    content: document.getElementById('message')
                  });

                  google.maps.event.addListener(map, 'click', function(event) {
                    if (marker){
                      marker.setPosition(event.latLng);
                    }else{
                      marker = new google.maps.Marker({
                        position: event.latLng,
                        map: map
                      });
                    }

                    google.maps.event.addListener(marker, 'click', function() {
                      infoWindow.open(map, marker);
                    });
                  });

                  $('#mapa').on('shown.bs.modal', function (e) {
                      google.maps.event.trigger(map, 'resize');
                      if (marker){
                        map.setCenter(marker.position);
                      }else{
                        // Try HTML5 geolocation.
                        if (navigator.geolocation) {
                          navigator.geolocation.getCurrentPosition(function(position) {
                            geolocation = {
                              lat: position.coords.latitude,
                              lng: position.coords.longitude
                            };

                            map.setCenter(geolocation);
                          }, function() {
                            handleLocationError(true, infoWindow, map.getCenter());
                          });
                        } else {
                          // Browser doesn't support Geolocation
                          handleLocationError(false, infoWindow, map.getCenter());
                        }
                      }
                  });

                  downloadUrl();
                }

                function loadMarker(data){
                  if(data == null || data == ""){
                    //setMapOnAll(null); // removes all markers
                    marker = null;
                  }else{
                    var point = new google.maps.LatLng(
                      parseFloat(data.lat),
                      parseFloat(data.lng));

                    marker = new google.maps.Marker({
                      position: point,
                      map: map
                    });
                  }
                }

                function downloadUrl(){
                  var url = '/revisitas/marcador';
                  $.ajax({
                    type: "GET",
                    url: url,
                    data: { 'revisita' : <?= $revisita['id'] ?> },
                    success: function(response){
                      loadMarker(response);
                    }
                  });
                }

                function saveData() {
                  var latlng = marker.getPosition();
                  var url = '/revisitas/marcador';
                  var lat = latlng.lat()
                  var lng = latlng.lng()
                  var revisita = <?= $revisita['id'] ?>;
                  var payload = { 'lat': lat, 'lng': lng, 'revisita': revisita };

                  postUrl(url, function(data, responseCode) {
                    if (responseCode == 200 && data.length <= 1) {
                      infoWindow.close();
                      messageWindow.open(map, marker);
                    }
                  }, payload);
                }

                function postUrl(url, callback, payload) {
                  $.ajax({
                    type: "POST",
                    url: url,
                    data: payload,
                    success: callback
                  });
                }

                function doNothing () {
                }

                function handleLocationError(browserHasGeolocation, infoWindow, pos) {
                  infoWindow.setPosition(pos);
                  infoWindow.setContent(browserHasGeolocation ?
                                        'Error: La localización falló.' :
                                        'Error: Su navegador no soporta geolocalización.');
                }

              </script>
              <script async defer
              src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAE9CtGzKmCpybzxlYEd3-EKBvCFbe185I&callback=initMap">
              </script>
            </body>
          </html>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="saveData();" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
