<?php
  $this->Html->css('dashboard', ['block' => true]);
  $this->extend('/Layout/admin');
  $this->Html->css('bootstrap-switch.min', ['block' => true]);
?>
<div class="row">
  <div class="col-lg-5 main">
    <h1 class="page-header">Edificio</h1>
    <?= $this->Form->create($edificio, ['class' => 'form-horizontal']) ?>
      <div class="form-group">
        <?= $this->Form->input('calle', ['class' => 'form-control', 'label' => ['text' => 'Calle'], 'required' => true]) ?>
      </div>
      <div class="form-group">
        <?= $this->Form->input('altura', ['class' => 'form-control', 'label' => ['text' => 'Altura'], 'required' => true]) ?>
      </div>
      <div class="form-group">
        <?= $this->Form->input('territorio', ['class' => 'form-control', 'label' => ['text' => 'Territorio'], 'required' => true]) ?>
      </div>
      <div class="form-group">
        <?= $this->Form->input('manzana', ['class' => 'form-control','placeholder' => 'Manzana', 'label' => ['text' => 'Manzana'],
        'options' => ['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D', 'e' => 'E', 'f' => 'F', 'g' => 'G'], 'required' => true]) ?>
      </div>
      <div class="form-group">
        <?= $this->Form->input(
        'pisos',
        array(
            'type' => 'select',
            'class' => 'form-control',
            'options' => array_combine(range(0,30),range(0,30)),
        )); ?>
          <div class="checkbox">
            <?= $this->Form->input('pisos_letras', ['type' => 'checkbox', 'label' => 'Pisos Letras', 'default' => false]) ?>
          </div>
          <div class="checkbox">
            <?= $this->Form->input('planta_baja', ['type' => 'checkbox', 'label' => 'Planta Baja', 'default' => true]) ?>
          </div>
      </div>
      <div class="form-group">
        <?= $this->Form->input('deptos',
          [
            'class' => 'form-control','placeholder' => 'Deptos',
            'label' =>
              [ 'text' => 'Departamentos'],
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
            <?= $this->Form->input('deptos_letras', ['type' => 'checkbox', 'label' => 'Deptos Letras', 'default' => true]) ?>
        </div>
        <div class="checkbox">
            <?= $this->Form->input('solo_pisos', ['type' => 'checkbox', 'label' => 'Solo Pisos', 'default' => false]) ?>
        </div>
        <div class="checkbox">
          <?= $this->Form->input('encargado', ['type' => 'checkbox', 'label' => 'Encargado']) ?>
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
        <div class="input">

        </div>
      </div>

        <?= $this->Form->hidden('excepciones', ['id' => 'excepciones', 'value' => $excepciones]); ?>
        <?= $this->Form->hidden('notocar', ['id' => 'notocar', 'value' => $notocar]); ?>
        <?= $this->Form->button(__('Guardar'), ['class' => 'btn btn-lg btn-primary btn-block']); ?>
        <br/>
        <?= $this->Form->end() ?>
  </div> <!-- col-lg-5 -->
  <div class="col-lg-7 main">
    <table id="plantilla" class="table">

    </table>
  </div>
</div> <!-- row -->
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
  $('#pisos-letras').on('click',function(){dibujar();});
  $('#deptos-letras').on('click',function(){
    $('#deptos-numerados').val(0)
    $('input[name=deptos_numerados]').prop('checked', false)
    dibujar();
  });
  $('#pisos').on('input',function(){dibujar();});
  $('#deptos').on('input',function(){dibujar();});
  $('#planta-baja').on('click',function(){ dibujar(); });
  $('#deptos-numerados').on('click',function(){
    $('#deptos-letras').val(0)
    $('input[name=deptos_letras]').prop('checked', false)
    dibujar();
  });

  $('#solo-pisos').on('click', function(){
    $('#deptos').val('1');
    dibujar();
  });

  $('#inicio-numeracion').on('change', function(){
    dibujar();
  });

  function setNotocar(){
      var result = [];
      var timbres = $('.timbre[data-exists=-1]');

      $.each(timbres, function(index){
          var one = $(timbres[index]).attr('data-matrix');
          result.push(one);
      });

      $('#notocar').val(JSON.stringify(result));
  }

  function setExcepciones(){
      var result = [];
      var timbres = $('.timbre[data-exists=0]');

      $.each(timbres, function(index){
        var one = $(timbres[index]).attr('data-matrix');
        result.push(one);
      });

    $('#excepciones').val(JSON.stringify(result));
  }

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
          result +='<td class="col-md-1"><button><span data-exists="'+exists+'" data-matrix="' +i+ ';' +j+ '" class="timbre glyphicon glyphicon-stop" style="color:'+color+'">'+ num_print +'</span></button></td>';
        }else{
          result += '<td class="col-md-1"><button><span data-exists="'+exists+'" data-matrix="' +i+ ';' +j+ '" class="timbre glyphicon glyphicon-stop" style="color:'+color+'"></span></button></td>';
        }
      }

      num = num - x;
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

    $('.timbre').on('click',function(){
        var status = $(this).attr('data-exists');
        if(status == 1){
            $(this).attr('style', 'color:red');
            $(this).attr('data-exists', 0);
        }else if(status == 0){
            $(this).attr('style', 'color:black');
            $(this).attr('data-exists', -1);
        }else if(status == -1){
            $(this).attr('style', 'color:green');
            $(this).attr('data-exists', 1);
        }

        setExcepciones();
        setNotocar();
    });

      setNotocar();
      setExcepciones();
  }

  dibujar();

}));
</script>
