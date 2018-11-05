<?php
  $this->Html->css('dashboard', ['block' => true]);
  $this->extend('/Layout/admin');
?>
<div class="row">
  <div class="col-lg-6 main">
    <h1 class="page-header">Revisitas</h1>
    <?= $this->Html->link('Nueva', ['action' => 'agregar'], ['class' => 'btn btn-primary']) ?>
  </div>
  <div class="col-lg-6">
      <form method="GET">
    <div class="input-group">
        <input id="q" name="q" type="text" class="form-control" autocomplete="off" placeholder="Buscar...">
        <span class="input-group-btn">
          <button id="search" class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
        </span>
    </div>
      </form>
  </div>
</div>
<div class="row" style="margin-top:10px;">
  <div class="col-md-12">
    <table class="table table-striped table-condensed">
      <tr><th><?php echo $this->Paginator->sort('fecha');?></th><th><?php echo $this->Paginator->sort('nombre');?></th><th class="hidden-xs">Comentarios</th><th>Direccion</th><th>Mapa</th><th>Acciones</th></tr>
      <?php foreach ($revisitas as $rev): ?>
        <tr>
          <td class="col-md-1"><?= date_format($rev->fecha, 'Y-m-d')?></td>
          <td class="col-md-2"><?= h($rev->nombre) ?></td>
          <td class="col-md-3 hidden-xs"><?= $this->Text->truncate($rev->comentarios, 50) ?></td>
          <td class="col-md-3"><?= h($rev->direccion) ?></td>
          <td class="col-md-1"><?php if($rev->marker){ echo $this->Html->link('Ver mapa', ['action' => '#'], ['onclick' => 'showMap('.$rev->id.');']); } ?></td>
          <td class="col-md-2"><?= $this->Html->link('Editar', ['action' => 'editar', $rev->id], ['class' => 'btn btn-xs btn-md btn-info']) ?>
          <?= $this->Html->link('Eliminar', ['action' => 'eliminar', $rev->id], ['confirm'=>'¿Seguro que desea eliminar la revisita?', 'class' => 'btn btn-xs btn-md btn-danger']) ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
    <nav>
      <ul class="pagination pagination-sm">
        <?php echo $this->Paginator->prev(' << '); ?>
        <?php echo $this->Paginator->numbers(); ?>
        <?php echo $this->Paginator->next(' >> '); ?>
      </ul>
    </nav>
  </div>
</div>

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
                var infowindow;
                var messagewindow;

                function showMap(id){
                  downloadUrl(id);
                  $('#mapa').modal();
                }

                function initMap() {
                  var bsas = {lat: -34.5847136, lng: -58.4435577};
                  map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 13,
                    center: bsas
                  });

                  infowindow = new google.maps.InfoWindow({
                    content: document.getElementById('form')
                  });
                  messagewindow = new google.maps.InfoWindow({
                    content: document.getElementById('message')
                  });

                  $('#mapa').on('shown.bs.modal', function (e) {
                      google.maps.event.trigger(map, 'resize');
                      map.setCenter(marker.position);
                  });
                }

                function loadMarker(data){
                  var point = new google.maps.LatLng(
                    parseFloat(data.lat),
                    parseFloat(data.lng));

                  marker = new google.maps.Marker({
                    position: point,
                    map: map
                  });
                }

                function downloadUrl(id){
                  var url = '/revisitas/marcador';
                  $.ajax({
                    type: "GET",
                    url: url,
                    data: { 'revisita' : id },
                    success: function(response){
                      loadMarker(response);
                    }
                  });
                }

                function doNothing () {
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
