<?php
  $this->Html->css('dashboard', ['block' => true]);
  $this->extend('/Layout/admin');
?>
  <div class="row">
    <div class="col-md-12">
      <h1 class="page-header">Visitas</h1>
      <?= $this->Html->link('Nueva', ['action' => 'agregar'], ['class' => 'btn btn-primary', 'disabled' => 'disabled']) ?>
    </div>
  </div>
  <div class="row" style="margin-top:10px;">
    <div class="col-md-12">
      <table class="table table-striped table-condensed">
        <tr><th><?php echo $this->Paginator->sort('fecha');?></th><th>Territorio</th><th>Direccion</th><th>Timbre</th><th>Atendio</th><th>Usuario</th><th>Acciones</th></tr>
        <?php foreach ($visitas as $visi): ?>
          <tr>
            <td class="col-md-2"><?= date_format($visi->fecha, 'Y-m-d H:i:s') ?></td>
            <td class="col-md-1"><?= h($visi->timbre->edificio->territorio) ?></td>
            <td class="col-md-3"><?= h($visi->timbre->edificio->calle . ' ' . $visi->timbre->edificio->altura) ?></td>
            <td class="col-md-1"><?php
              if($visi->timbre->row == 0){ echo 'PB '; }else{ if($visi->timbre->edificio->pisos_letras) {echo $alphabet[$visi->timbre->row]. 'º ';}else{ echo $visi->timbre->row. 'º ';} }
              if(!$visi->timbre->edificio->solo_pisos){
                if($visi->timbre->edificio->deptos_letras){
                  echo $alphabet[$visi->timbre->col];
                }else{
                  echo $visi->timbre->col;
                }
              }
              ?></td>
            <td class="col-md-1"><?= $visi->atendio ? 'Si' : 'No' ?></td>
            <td class="col-md-1"><?= $visi->user->username ?></td>
            <td class="col-md-2"><?= $this->Html->link('Eliminar', ['action' => 'eliminar', $visi->id], ['confirm'=>'¿Seguro que desea eliminar la visita?', 'class' => 'btn btn-danger']) ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
      <nav>
        <ul class="pagination">
          <?php echo $this->Paginator->prev(' << ' . __('Anterior')); ?>
          <?php echo $this->Paginator->numbers(); ?>
          <?php echo $this->Paginator->next(__('Siguiente') . ' >> '); ?>
        </ul>
      </nav>
    </div>
  </div>