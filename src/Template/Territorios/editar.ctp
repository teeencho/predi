<?php
  $this->Html->css('dashboard', ['block' => true]);
  $this->extend('/Layout/admin');
  $this->Html->css('bootstrap-switch.min', ['block' => true]);
?>
<div class="row">
  <h1 class="page-header">Territorio</h1>
  <div class="col-lg-5 main">
    <?= $this->Form->create($territorio, ['class' => 'form-horizontal', 'enctype'=>'multipart/form-data']) ?>
      <div class="form-group">
        <?= $this->Form->input('numero', ['class' => 'form-control', 'label' => ['text' => 'Numero'], 'required' => true]) ?>
      </div>
      <div class="form-group">
        <?= $this->Form->control('file', ['class' => 'form-control', 'type' => 'file', 'label' => ['text' => 'Imagen'], 'required' => true]) ?>
      </div>
        <?= $this->Form->button(__('Guardar'), ['class' => 'btn btn-lg btn-primary btn-block']); ?>
        <br/>
        <?= $this->Form->end() ?>
        <div class="alert alert-info">
          <strong>Importante!</strong> Los formatos permitidos para los territorios son jpeg o png.
          También se pueden subir imágenes desde dispositivos móviles.
        </div>
    </div> <!-- col-lg-5 -->
    <div class="col-lg-5 main">
      <?php echo $this->Image->display($territorio->file, 'medium'); ?>
    </div>
</div> <!-- row -->
