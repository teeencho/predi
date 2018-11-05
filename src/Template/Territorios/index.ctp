<?php
  $this->Html->css('dashboard', ['block' => true]);
  $this->extend('/Layout/admin');
?>
<div class="row">
  <div class="col-lg-6 main">
    <h1 class="page-header">Edificios</h1>
    <?= $this->Html->link('Nuevo', ['action' => 'agregar'], ['class' => 'btn btn-primary']) ?>
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
      <tr><th><?php echo $this->Paginator->sort('territorio');?></th><th><?php echo $this->Paginator->sort('manzana');?></th><th>Dirección</th><th>Acciones</th></tr>
      <?php foreach ($edificios as $edi): ?>
        <tr>
          <td class="col-md-1"><?= strtoupper(h($edi->territorio)) ?></td>
          <td class="col-md-1"><?= strtoupper(h($edi->manzana)) ?></td>
          <td class="col-md-3"><?= h($edi->calle . ' ' . $edi->altura) ?></td>
          <td class="col-md-2"><?= $this->Html->link('Editar', ['action' => 'editar', $edi->id], ['class' => 'btn btn-md btn-xs btn-info']) ?>
          <?= $this->Html->link('Eliminar', ['action' => 'eliminar', $edi->id], ['confirm'=>'¿Seguro que desea eliminar el edificio?', 'class' => 'btn btn-md btn-xs btn-danger']) ?></td>
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
