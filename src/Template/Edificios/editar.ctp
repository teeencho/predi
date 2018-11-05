<?php
  $this->Html->css('dashboard', ['block' => true]);
  $this->extend('/Layout/admin');
  $this->Html->css('bootstrap-switch.min', ['block' => true]);
  $this->Html->script('bootstrap-switch.min', ['block' => true]);
?>
<div class="row">
  <div class="col-lg-5 main">
    <h1 class="page-header">Edificio</h1>
    <?= $this->Form->create($edificio, ['class' => 'form-horizontal']) ?>
      <div class="form-group">
        <?= $this->Form->input('calle', ['required' => true,'class' => 'form-control', 'label' => ['text' => 'Calle']]) ?>
      </div>
      <div class="form-group">
        <?= $this->Form->input('altura', ['required' => true,'class' => 'form-control', 'label' => ['text' => 'Altura']]) ?>
      </div>
      <div class="form-group">
        <?= $this->Form->input('territorio', ['required' => true,'class' => 'form-control', 'label' => ['text' => 'Territorio']]) ?>
      </div>
      <div class="form-group">
        <?= $this->Form->input('manzana', ['required' => true,'class' => 'form-control','placeholder' => 'Manzana', 'label' => ['text' => 'Manzana'],
        'options' => ['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D', 'e' => 'E', 'f' => 'F', 'g' => 'G']]) ?>
      </div>
      <div class="form-group">
        <?= $this->Form->input(
        'pisos',
        array(
            'class' => 'form-control',
            'disabled' => 'disabled'
        )); ?>
          <div class="checkbox">
            <?= $this->Form->input('pisos_letras', ['type' => 'checkbox', 'label' => 'Pisos Letras', 'disabled' => 'disabled']) ?>
          </div>
          <div class="checkbox">
            <?= $this->Form->input('planta_baja', ['type' => 'checkbox', 'label' => 'Planta Baja', 'disabled' => 'disabled']) ?>
          </div>
      </div>
      <div class="form-group">
        <?= $this->Form->input('deptos',
          [
            'class' => 'form-control','placeholder' => 'Deptos',
            'label' =>
              [ 'text' => 'Departamentos'],
              'disabled' => 'disabled',
            'options' => [
                1 => "A",
                2 => "B",
                3 => "C",
                4 => "D",
                5 => "E",
                6 => "F",
                7 => "G",
                8 => "H",
                9 => "I",
                10 => "J",
                11 => "K",
                12 => "L",
                13 => "M",
                14 => "N",
                15 => "O",
                16 => "P",
                17 => "Q",
                18 => "R",
                19 => "S",
                20 => "T"
                ]
          ]) ?>
        <div class="checkbox">
            <?= $this->Form->input('deptos_letras', ['type' => 'checkbox', 'label' => 'Deptos Letras', 'disabled' => 'disabled']) ?>
        </div>
        <div class="checkbox">
            <?= $this->Form->input('solo_pisos', ['type' => 'checkbox', 'label' => 'Solo Pisos']) ?>
        </div>
      </div>
      <?= $this->Form->hidden('excepciones', ['id' => 'excepciones', 'value' => $excepciones]); ?>
      <?= $this->Form->hidden('notocar', ['id' => 'notocar', 'value' => $notocar]); ?>
      <div class="form-group">
        <div class="checkbox">
          <?= $this->Form->input('encargado', ['type' => 'checkbox', 'label' => 'Encargado', 'disabled' => 'disabled']) ?>
        </div>
        <div class="checkbox">
          <?= $this->Form->input('deptos_numerados', ['type' => 'checkbox', 'label' => 'Deptos Numerados']) ?>
          <?= $this->Form->input(
            'inicio_numeracion',
              [
                'label' => '',
                'type' => 'select',
                'class' => '',
                'options' => array_combine(range(1,5),range(1,5)),
            ]); ?>
        </div>
      </div>
      <div class="form-group">
        <?= $this->Form->button(__('Guardar'), ['class' => 'btn btn-lg btn-primary btn-block']); ?>
      </div>
    <?= $this->Form->end() ?>
  </div> <!-- col-lg-5 -->
  <div class="col-lg-7 main">
    <table id="plantilla" class="table">

    </table>
  </div>
</div> <!-- row -->
<div class="row">
  <div class="col-lg-6">
    <h2 class="page-header">Timbres</h1>
      <table class="table table-striped table-condensed">
        <tr><th>Piso</th><th>Depto</th><th>Nombre/Numeracion</th><th>Acciones</th></tr>
        <?php foreach ($edificio['timbres'] as $tim): ?>
          <tr>
            <td class="col-md-1"><?php if($tim->row == 0){ echo 'PB';}else{ echo $edificio['pisos_letras'] ? $alphabet[$tim->row] : $tim->row; } ?></td>
            <td class="col-md-1"><?php if($tim->col != 0){ echo !$edificio['deptos_letras'] ? $tim->col : $alphabet[$tim->col-1];} ?></td>
            <td class="col-md-1"><input type="text" data-id="<?= $tim->id ?>"  maxlength="10" class="form-control input-sm" placeholder="Nombre" value="<?php if($tim->nombre){ echo $tim->nombre; }?>"></input></td>

            <td class="col-md-3">
              <a href="#" "data-id"="<?= $tim->id ?>" class="guardar-timbre btn-sm btn-info">Guardar</a>
              <?php
              if(!$tim['notocar']){
                echo $this->Html->link('No Tocar', ['action' => 'no_tocar', $tim->id], ['class' => 'btn-sm btn-warning']);
              }else{
                echo $this->Html->link('Tocar', ['action' => 'no_tocar', $tim->id], ['class' => 'btn-sm btn-info']);
              }
              ?>
            <?= $this->Html->link('Eliminar', ['action' => 'eliminar_timbre', $tim->id], ['class' => 'btn-sm btn-danger','confirm'=>'¿Seguro que desea eliminar el timbre? Esta accion no se puede deshacer.']) ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
  </div>
  <div class="col-lg-3">
    <h2 class="page-header">Agregar Timbre</h1>
    <form id="agregar_timbre" method="POST">
     <div class="form-group">
        <?= $this->Form->input('piso', ['autocomplete'=> 'off','maxlength'=> '2', 'required' => true, 'class' => 'form-control','style="text-transform:uppercase"', 'label' => ['text' => 'Piso']]) ?>
      </div>
      <div class="form-group">
        <?= $this->Form->input('depto', ['autocomplete'=> 'off', 'maxlength'=> '2','required' => true, 'class' => 'form-control', 'style="text-transform:uppercase"','placeholder' => 'Solo numeros Ej. A = 1', 'label' => ['text' => 'Depto']]) ?>
      </div>
      <div class="form-group">
        <?= $this->Form->input('nombre', ['autocomplete'=> 'off', 'maxlength'=> '4','required' => false, 'class' => 'form-control', 'style="text-transform:uppercase"','placeholder' => '', 'label' => ['text' => 'Nombre/Numeracion']]) ?>
      </div>
      <div class="form-group">
        <button class="btn btn-lg btn-primary btn-block">Guardar</button>
      </div>
    </form>
  </div>
<style>
#plantilla{
  font-size: 2em;
}

#plantilla th {
  text-align: center;
}

#plantilla button{
  background-color: Transparent;
  background-repeat:no-repeat;
  border: none;
}
</style>
<script>
($(document).ready(function(){
  $('#pisos-letras').on('click',function(){
      dibujar();
  });

  $('#deptos-letras').on('click',function(){
      dibujar();
  });

  function dibujar(){
    var div = $('#plantilla');
    var result="";
    var y = parseInt($('#pisos').val());
    var x = parseInt($('#deptos').val());
    var pisos_letras = $('#pisos-letras').is(':checked');
    var deptos_letras = $('#deptos-letras').is(':checked');
    var planta_baja = $('#planta-baja').is(':checked');
    var deptos_numerados = $('#deptos-numerados').is(':checked');
    var solo_pisos = $('#solo-pisos').is(':checked');
    var letras = [
      'A','B','C','D','E','F','G','H','I','J', 'K', 'L',
      'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T' ,'U', 'V', 'W', 'X', 'Y', 'Z'
    ];
    var excepciones = $('#excepciones').val() ? JSON.parse($('#excepciones').val()) : null;
    var notocar = $('#notocar').val() ? JSON.parse($('#notocar').val()) : null;
    var comienzo = parseInt($('#inicio-numeracion').val());
    var num = planta_baja ? x*y + x +comienzo -1 : x*y +comienzo -1;

    $(div).empty();
    var stop = planta_baja ? 0 : 1;
    for(i=y; i>=stop; i--){
      result += '<tr class="row">';

      for(j=1; j<=x; j++){
        if(j==1){
          if(planta_baja && i == 0){
            result += '<td class="col-md-1">PB</td>';
          }else if(parseInt(pisos_letras) == 1 || pisos_letras == true || pisos_letras == 'true'){
            result += '<td class="col-md-1">'+(letras[i-1])+'</td>';
          }else if(planta_baja == false && i == 0){
            continue;
          }else{
            result += '<td class="col-md-1">'+(i)+'</td>';
          }
        }

        var exists = 1;
        var color = 'green';
        if(excepciones){
          $.each(excepciones, function(index, value){
            if((i+";"+j) == value){
              exists = 0;
              color = 'red';
              return false;
            }
          });
       }
       if(notocar){
        $.each(notocar, function(index, value){
          if((i+";"+j) == value){
            exists = -1;
            color = 'black';
            return false;
          }
        });
      }

        if(deptos_numerados){
          num_print = num - (x - j);

          result += '<td class="col-md-1"><button><span data-exists="'+exists+'" data-matrix="' +i+ ';' +j+ '" class="timbre glyphicon glyphicon-stop" style="color:'+color+'">'+ num_print +'</span></button></td>';
        }else{
          result += '<td class="col-md-1"><button><span data-exists="'+exists+'" data-matrix="' +i+ ';' +j+ '" class="timbre glyphicon glyphicon-stop" style="color:'+color+'"></span></button></td>';
        }
      }

      num = num -x;
      result += '</tr>';
    }
    if(!solo_pisos){
      if(!deptos_numerados){
        var deptos='<thead><tr class="row"><th class="col-md-1"></th>';

        for(j=1; j<=x; j++){
          if(parseInt(deptos_letras) == 1 || deptos_letras == true || deptos_letras == 'true'){
            deptos += '<th>'+letras[j-1]+'</th>';
          }else{
            deptos += '<th>'+j+'</th>';
          }
        }

        deptos += '</tr></thead>';
        result = deptos + result;
      }
    }
    $(div).append(result);
  }

  dibujar();

  $('.guardar-timbre').click(function(event){
      //var id = $(event.currentTarget).data('id');
      var id = event.currentTarget.attributes[1]['value'];
      var value = $('input[data-id="'+id+'"]').val();
      $.ajax({
        type: 'POST',
        url: '/edificios/editar_timbre',
        data: {
          'timbre': id,
          'nombre': value
        },
        success: function(data){
        },
        error: function(data){
          alert('Ha ocurrido un error inesperado, vuelva a intentarlo.' +
            'Si el problema persiste contacte al administrador');
        }
      });
  });

  $("#agregar_timbre").submit(function(event) {
      event.preventDefault();
      var edificio = <?= $edificio['id'] ?>;
      var piso = $('#piso').val().toUpperCase();
      var depto = $('#depto').val().toUpperCase();
      var nombre = $('#nombre').val().toUpperCase();

      if(piso == 'PB'){
        piso = 0;
      }else if(parseInt(piso) == NaN){
        piso = piso.charCodeAt(0) - 64;
      }

      if(parseInt(depto) == NaN){
        if(depto.length > 1){
          alert('No se permiten deptos con mas de una letra');
          return;
        }else{
          depto = depto.charCodeAt(0) - 64;
        }
      }

      $.ajax({
        type: 'POST',
        url: '/edificios/agregar_timbre',
        data: {
          'edificio': edificio,
          'piso': piso,
          'depto': depto,
          'nombre': nombre
        },
        success: function(data){
          window.location = window.location;
        },
        error: function(data){
          alert('Ha ocurrido un error inesperado, vuelva a intentarlo.' +
            'Si el problema persiste contacte al administrador');
        }
    });
  });
}));
</script>
