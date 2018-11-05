<?php
  $this->extend('/Layout/default');
  $this->Html->css('dashboard', ['block' => true]);
?>
  <div class="col-sm-12 col-md-12 col-lg-12 main">
    <h1 class="page-header"><?= $header ? $header : 'Visitar' ?></h1>
    <div class="row">
      <div class="col-lg-6 col-sm-8 col-xs-12">
        <div class="input-group">
          <input id="q" name="q" type="text" class="form-control" autocomplete="off" placeholder="Buscar...">
        <span class="input-group-btn">
          <button id="search" class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
        </span>
        </div><!-- /input-group -->
        <div class="tt-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100;">
          <div class="tt-dataset">
          </div>
        </div>
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
                  <li class="previous"><a href="/edificios/visitar"><span class="glyphicon glyphicon-arrow-left"></span> Salir</a></li>
                  <!--<li class="next"><a href="#" id="tocar_otro">Otro <span class="glyphicon glyphicon-arrow-right"></span></a></li>-->
                </ul>
              </nav>
              <h4>Por favor llame el timbre:</h4>
              <h2>
                <span class="piso"></span> <span class="depto"></span>
              </h2>
              <span id="ultima" class="help-block"></span>
            </div>
            <div class="panel-footer">
              <button id="atendio" data-atendio="1" class="btn btn-lg btn-success">Atendió</button>
              <button id="noatendio" data-atendio="0" class="btn btn-lg btn-danger">No Atendió</button>
            </div>
          </div>
        </div>
      </div>
      <div class="row tabla-timbres">
        <div class="col-sm-12 col-lg-6 col-lg-offset-">
          <h4 class="page-header">Modo manual</h1>
            <div class="alert alert-info">
              <strong>Atención!</strong> Utilice el modo manual si desea llamar timbres por su propio orden.
              Elija <strong>✓</strong> si lo atendieron o <strong>x</strong> si no lo atendieron.
            </div>
            <table id="timbres-todos" class="table table-striped table-condensed">
              <thead><tr><th>Timbre</th><th>Acciones</th><th></th><th>Ult. Visita</th></tr></thead>
              <tbody>
              </tbody>
            </table>
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

.tabla-timbres{
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
    var letras = [
      'A','B','C','D','E','F','G','H','I','J', 'K', 'L',
      'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T' ,'U', 'V', 'W', 'X', 'Y', 'Z'
    ];

    var edificioId = <?php echo $edificioId; ?>;

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
      $('.plantilla').hide();
      $('#referencia').hide();

      var contenedor = $('.wizard');
      $.ajax({
        type: 'POST',
        url: '/edificios/traer_timbre',
        data: {'edificio': id},
        success: function(data){
          var piso = $('span.piso');
          var depto = $('span.depto');
          var ultima = $('#ultima');
          var edificio = data[0];
          var timbre = edificio.timbres[0];
          if(timbre == undefined){
            piso.text("No hay mas timbres por tocar");
            depto.empty();
            ultima.empty();
            return;
          }

          if(timbre.ultima_visita){
            ultima.text('Ultima visita: '+ timbre.ultima_visita.substring(0,10));
          }else{
            ultima.empty();
          }

           nombre = timbre_nombre(edificio, timbre);
           piso.text(nombre['piso']);
           depto.text(nombre['depto']);

          $('#atendio').attr('data-timbre', timbre.id);
          $('#noatendio').attr('data-timbre',timbre.id);

          $('.wizard button').on('click', function(){
            $.ajax({
              type: 'POST',
              url: '/edificios/tocar_timbre',
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
          });
        }
      });
    }

    function cargarPlantilla(id){
      $('.wizard').hide();
      $('.plantilla').show();
      $('#referencia').show();

      var contenedor = $('#plantilla');
      $.ajax({
        type: 'POST',
        url: '/edificios/traer_timbres',
        data: {'edificio': id},
        success: function(data){
          var result="";
          $(contenedor).empty();
          $(contenedor).show();

          for(i=data.timbres.length-1; i>=0; i--){
            if(i==data.timbres.length-1){
              result += '<tr class="row">';
            }

            if(typeof data.timbres[i].porcentaje === 'undefined'){
              var timbreColor= "rgb(148, 148, 148)"
            }else{
              var n = Math.round(data.timbres[i].porcentaje * 2.55);
              var timbreColor= "rgb(255, " + (255 - n) +", 0)"
            }

            var atendioClass = 'glyphicon-stop';
            var atendio = data.timbres[i].atendio;
            if(atendio){
              atendioClass = 'glyphicon-ok';
            }else{
              atendioClass = 'glyphicon-remove';
            }

            result += '<td class="col-md-1"><button data-id="'+ data.timbres[i].id +'" data-atendio="'+atendio+'"><span style="color:'+ timbreColor +'" class="timbre glyphicon '+ atendioClass +'"></span></button></td>';
            if(i==0){
              result += '</tr>';
            }else{
              if(data.timbres[i].row != data.timbres[i-1].row){
                result += '</tr>';
                result += '<tr class="row">';
              }
            }
          }

          $(contenedor).append(result);

          $('#plantilla button').click(function(){
            var atendio = $(this).attr('data-atendio');
            if(atendio == "false"){
              atendio = true
              $(this).find('span').removeClass('glyphicon-remove');
              $(this).find('span').addClass('glyphicon-ok');
            }else{
              atendio = false;
              $(this).find('span').addClass('glyphicon-remove');
              $(this).find('span').removeClass('glyphicon-ok');
            }
            $(this).attr('data-atendio', atendio);

            $.ajax({
              type: 'POST',
              url: '/edificios/tocar_timbre',
              data: {
                'timbre': $(this).attr('data-id'),
                'atendio': $(this).attr('data-atendio')
              }
            });
          });
        }
      });
    }

    function cargarTabla(edi){
      var tabla = $('#timbres-todos tbody');
      $(tabla).empty();

      $.ajax({
        type: 'POST',
        url: '/edificios/traer_timbres',
        data: {'edificio': edi},
        success: function(data){
          $('.tabla-timbres').show();
          timbres = data.timbres
          $.each(timbres, function(key, val){
            var tr = '<tr>';
            var fecha;
            var clase_atendio = '';
            var clase_noatendio = '';

            if(val.ultima_visita){
              fecha = val.ultima_visita.substring(0,10);
              today = new Date().toJSON().substring(0,10);

              var hoy = (fecha == today);
              var ultima_visita_style = ""
              if(val.visitas.length > 0){
                atendio = val.visitas[0].atendio;
                if(hoy){
                  if(atendio){
                    clase_atendio = 'btn-success';
                    clase_noatendio = '';
                  }else if(atendio === false){
                    clase_atendio = '';
                    clase_noatendio = 'btn-danger';
                  }
                }
                ultima_visita_style = atendio ? 'glyphicon glyphicon-ok-circle text-success' : 'glyphicon glyphicon-remove-circle text-danger';
              }
            }

            nombre = timbre_nombre(data, val);
            tr += '<td>'+nombre['piso']+' '+nombre['depto']+'</td>';
            tr += '<td><button id="atendio-'+val.id+'" data-timbre="'+ val.id +'" data-atendio="1" class="btn btn-md visita-manual '+ clase_atendio+'"><span class="glyphicon glyphicon-ok"></span></button></td>';
            tr += '<td><button id="noatendio-'+val.id+'" data-timbre="'+ val.id +'" data-atendio="0" class="btn btn-md visita-manual '+ clase_noatendio +'"><span class="glyphicon glyphicon-remove"></span></button></td>';
            if (fecha){tr += '<td>'+val.ultima_visita.substring(0,10)+' <span class="'+ultima_visita_style+'"></span></td>';}else{tr += '<td></td>';}
            tr += '</tr>';
            $(tabla).append(tr);
          });

          $('button.visita-manual').on('click',function(){
            $.ajax({
              type: 'POST',
              url: '/edificios/tocar_timbre',
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
          });
        }
      });
    }

    if(edificioId){
      cargarWizard(edificioId);
      cargarTabla(edificioId);
    }

    function timbre_nombre(edificio, timbre){
      var nombre = {};
      if(timbre.nombre == 'Encargado'){
        nombre['piso'] = '';
        nombre['depto'] = 'Encargado';
        return nombre;
      }
      if(timbre.row == 0){
        nombre['piso'] = 'PB';
      }else{
        nombre['piso'] = (edificio.pisos_letras ? letras[timbre.row-1] : timbre.row + 'º');
      }
      if(timbre.nombre){
        nombre['depto'] = timbre.nombre;
        return nombre;
      }else{
        if(edificio.deptos_numerados){
          nombre['depto'] = timbre.col;
        }else{
          if(!edificio.solo_pisos){
            nombre['depto'] = (edificio.deptos_letras ? letras[timbre.col-1] : timbre.col);
          }else{
            nombre['depto'] = '';
          }
        }
      }

      return nombre;
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
              var sel = '<a id="elegir_'+data[i].id+'" data-id="'+data[i].id+'" href="/edificios/visitar/'+ data[i].id +'"><div class="tt-suggestion tt-selectable">'+
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
