<?php
  $this->Html->css('dashboard', ['block' => true]);
  $this->extend('/Layout/admin');
  $this->Html->css('bootstrap-switch.min', ['block' => true]);
  $this->Html->script('bootstrap-switch.min', ['block' => true]);
  $this->Html->css('morris', ['block' => true]);
  $this->Html->script('raphael.min', ['block' => true]);
  $this->Html->script('morris.min', ['block' => true]);
?>
<div class="row">
    <div class="col-md-12">
      <h1 class="page-header">Estadisticas</h1>
    </div>
</div>
<div class="row" style="margin-top:10px;">
    <div class="col-md-6">
      <h3>Territorios más visitados</h3>
      <div id="masVisitados">
      </div>
    </div>
    <div class="col-md-6">
      <h3>Últimos 30 días</h3>
      <div id="cobertura">
      </div>
    </div>
</div>
  <div class="row" style="margin-top:10px;">
    <div class="col-md-12">
      <h3>% Cobertura por Territorio en los últimos 60 días</h3>
      <div id="completados">
      </div>
    </div>
  </div>

<style>

</style>
<script>
($(document).ready(function(){

  $.ajax({
    url : '/edificios/completados',
    type : 'POST',
    success: function(data){
      Morris.Bar({
        element: 'completados',
        data: data,
        xkey: 'territorio',
        ykeys: ['perc'],
        labels: ['%']
      });
    }
  });

  $.ajax({
    url : '/edificios/masVisitados',
    type : 'POST',
    success: function(data){
      Morris.Bar({
        element: 'masVisitados',
        data: data,
        xkey: 'territorio',
        ykeys: ['count'],
        labels: ['Visitas']
      });
    }
  });

  $.ajax({
    url : '/edificios/cobertura',
    type : 'POST',
    success: function(results){
      Morris.Donut({
        element: 'cobertura',
        data: [
          {label: 'Atendieron', value: results.visitasAtendieronCount},
          {label: 'No Atendieron', value: results.visitasNoAtendieronCount}
        ],
        colors: ['#00e673', '#ff5050']
      });
    }
  });

}));
</script>
