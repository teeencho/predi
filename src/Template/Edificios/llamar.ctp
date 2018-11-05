<?php
  $this->extend('/Layout/default');
  $this->Html->css('dashboard', ['block' => true]);
  $this->Html->css('bootstrap-switch.min', ['block' => true]);
  $this->Html->script('bootstrap-switch.min', ['block' => true]);
?>
  <div class="col-sm-12 col-md-12 col-lg-12 main">
    <h1 class="page-header"><?= $header ? $header : 'Llamar' ?></h1>
    <div class="row">
      <div class="col-lg-6 col-sm-8 col-xs-12">
        <div class="input-group">
          <input id="q" name="q" type="text" class="form-control" autocomplete="off" placeholder="Buscar dirección...">
          <span class="input-group-btn">
            <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
          </span>
          <div class="tt-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100;">
            <div class="tt-dataset">
            </div>
          </div>
        </div><!-- /input-group -->
      </div>
    </div>
    <div id="referencia" class="row well well-sm">
      <div class="col-md-2"><span style="color:rgb(255, 215, 0)" class="glyphicon glyphicon-stop"> Menos visitas</span></div>
      <div class="col-md-2"><span style="color:rgb(255, 0, 0)" class="glyphicon glyphicon-stop"> Mas visitas</span></div>
      <div class="col-md-2"><span style="color:rgb(148, 148, 148)" class="glyphicon glyphicon-stop"> Sin visitas</span></div>
      <div class="col-md-2"><span class="glyphicon glyphicon-ok"> Atendio</span></div>
      <div class="col-md-2"><span class="glyphicon glyphicon-remove"> No atendio</span></div>
    </div>
    <div class="row wizard">
        <div class="col-lg-4 col-sm-12 col-lg-offset-4">
          <div class="panel panel-info">
            <div class="panel-heading">Timbre</div>
            <div class="panel-body">
              <nav>
              <ul class="pager">
                <li class="previous"><a href="/edificios/visitar"><span class="glyphicon glyphicon-arrow-left"></span> Volver sin tocar</a></li>
              </ul>
            </nav>
              <h4>Por favor llame al numero:</h4>
              <h2>
                <span class="piso"></span> <span class="depto"></span>
              </h2>
            </div>
            <div class="panel-footer">
              <button id="atendio" data-atendio="1" class="btn btn-lg btn-success">Atendió</button>
              <button id="noatendio" data-atendio="0" class="btn btn-lg btn-danger">No Atendió</button>
            </div>
          </div>
        </div>
      </div>
  </div>
<style>
.row{
  margin-bottom: 20px;
}

.panel-body{
  text-align: center;
}

.pager{
  margin: 0;
}

.tt-menu{
  z-index: 1000;
  display:none;
  width:100%;
  margin: 0.5em 0;
  padding: 8px 0;
  border: 1px solid rgba(0, 0, 0, 0.2);
  border-radius: 8px;
  box-shadow: 0 5px 10px rgba(0,0,0,.2);
  background-color: white;
  text-decoration: none;
}

.tt-suggestion{
  padding: 3px 20px;
}

.tt-suggestion:hover {
  cursor: pointer;
  color: #fff;
  background-color: #0097cf;
}

.wizard{
  display: none;
}

.switch{
  display: none;
}

#plantilla{
  font-size: 2em;
}

#plantilla button{
  background-color: Transparent;
  background-repeat:no-repeat;
  border: none;
}

#plantilla .col-lg-1{
  width: 10%;
}

.plantilla{
  display: none;
}

#referencia{
  display: none;
}

</style>
<script>
  ($(document).ready(function(){
    var letras = ['A','B','C','D','E','F','G','H','I','J'];
    // descomentar para cargar plantilla automaticamente
    var edificioId = <?= $edificioId ?>;

    $("#q").on('keyup', function(key){
      switch (key.which){
          case 13:
          case 40:
          case 38:
          case 39:
          case 37:
            break;

          default:
              clearTimeout($.data(this, 'timer'));
              $(this).data('timer', setTimeout(buscar, 600));
      }
    });

    function cargarWizard(id){
      $('.wizard').show();
      $('#referencia').hide();

      $.ajax({
        type: 'POST',
        url: '/edificios/traer_timbre',
        data: {'edificio': id},
        success: function(data){
          var piso = $('span.piso');
          var depto = $('span.depto');
          var edificio = data[0];
          var timbre = edificio.timbres[0];

          depto.text(edificio.deptos_letras ? letras[timbre.col-1] : timbre.col);

          if(timbre.row == 0 && edificio.planta_baja){
            piso.text('PB')
          }else{
            piso.text((edificio.pisos_letras ? letras[timbre.row-1] : timbre.row) + 'º');
          }

          $('#atendio').attr('data-timbre', timbre.id);
          $('#noatendio').attr('data-timbre',timbre.id);

          $('.wizard button').click(function(){
            $.ajax({
              type: 'POST',
              url: '/edificios/llamar_timbre',
              data: {
                'timbre': $(this).attr('data-timbre'),
                'atendio': $(this).attr('data-atendio')
              },
              success: function(){
                window.location = window.location;
              },
              error: function(){
                alert('Ha ocurrido un error inesperado');
              }
            });
          })
        }
      });
    }

    if(edificioId){
      cargarWizard(edificioId);
    }

    function buscar(){
      var text = $('#q').val();
      $.ajax({
        type: 'POST',
        url: '/edificios/buscar',
        data: { 'q' : text },
        success: function(data){
          if(data.length > 0){
            var dataset = $('.tt-dataset');
            $(dataset).empty();

            for(i=0; i<data.length;i++){
              var sel = '<a id="elegir_'+data[i].id+'" data-id="'+data[i].id+'" href="/edificios/llamar/'+ data[i].id +'"><div class="tt-suggestion tt-selectable">'+
                          '<strong class="tt-highlight">' + data[i].calle + ' ' + data[i].altura + '</strong>'+
                          '</div></a>';
              $(dataset).append(sel);
            }

            $('.tt-menu').show();
          }else{
            $('.tt-menu').hide();
          }
        }
      });
    }
  }));
</script>