<?php
  $this->Html->css('dashboard', ['block' => true]);
  $this->extend('/Layout/admin');
  $this->Html->css('bootstrap-switch.min', ['block' => true]);
?>
<div class="row">
  <div class="col-lg-6 main">
    <h1 class="page-header">Revisitas</h1>
    <?= $this->Form->create($revisita, ['class' => 'form-horizontal']) ?>
      <div class="form-group">
        <?= $this->Form->input('nombre', ['class' => 'form-control', 'label' => ['text' => 'Nombre'], 'required' => true]) ?>
      </div>
      <div class="form-group">
        <?= $this->Form->input('direccion', ['class' => 'form-control', 'label' => ['text' => 'Direccion'], 'required' => true]) ?>
      </div>
      <div class="form-group">
        <?= $this->Form->textarea('comentarios', ['class' => 'form-control', 'placeholder' => 'Comentarios', 'label' => ['text' => 'Comentarios'], 'required' => true]) ?>
      </div>
      <div class="form-group">
        <?= $this->Form->input('clase', ['class' => 'form-control','placeholder' => 'Clase', 'label' => ['text' => 'Clase'],
        'options' => ['0' => 'Interesado', '1' => 'Revisita', '2' => 'Estudio'], 'required' => true]) ?>
      </div>
      <?= $this->Form->hidden('fecha', ['id' => 'fecha', 'value' => date('Y-m-d')]); ?>
    <?= $this->Form->button(__('Guardar'), ['class' => 'btn btn-lg btn-primary btn-block']); ?>
    <br/>
    <?= $this->Form->end() ?>
  </div> <!-- col-lg-6 -->
</div> <!-- row -->
