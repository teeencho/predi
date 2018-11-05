<?php
  $this->Html->css('dashboard', ['block' => true]);
  $this->extend('/Layout/admin');
?>
  <div class="row">
    <div class="col-md-12">
      <h1 class="page-header">Llamadas</h1>
      <?= $this->Html->link('Nueva', ['action' => 'agregar'], ['class' => 'btn btn-primary']) ?>
    </div>
  </div>
  <div class="row" style="margin-top:10px;">
    <div class="col-md-12">
      <table class="table table-striped table-condensed">
        <tr><th>Fecha</th><th>Territorio</th><th>Direccion</th><th>Timbre</th><th>Atendio</th><th>Usuario</th><th>Acciones</th></tr>
        <?php foreach ($llamadas as $llama): ?>
          <tr>
            <td class="col-md-2"><?= date_format($llama->fecha, 'Y-m-d H:i:s') ?></td>
            <td class="col-md-1"><?= h($llama->timbre->edificio->territorio) ?></td>
            <td class="col-md-3"><?= h($llama->timbre->edificio->calle . ' ' . $llama->timbre->edificio->altura) ?></td>
            <td class="col-md-1"><?= h($llama->timbre->row . $llama->timbre->col) ?></td>
            <td class="col-md-1"><?= $llama->atendio ? 'Si' : 'No' ?></td>
            <td class="col-md-1"><?= $llama->user->username ?></td>
            <td class="col-md-2"><?= $this->Html->link('Eliminar', ['action' => 'eliminar', $llama->id], ['confirm'=>'¿Seguro que desea eliminar la llamada?']) ?></td>
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