<?php
	$this->Html->css('signin', ['block' => true]);
    $this->extend('/Layout/admin');
?>

<div class="users form">
<?= $this->Form->create($user,['class' => 'form-signin']) ?>
    <fieldset>
        <legend><?= __('Add User') ?></legend>
        <?= $this->Form->input('username',
        ['class' => 'form-control','placeholder' => 'User', 'label' =>
        ['class' => 'sr-only', 'text' => 'User']]) ?>
        <?= $this->Form->input('password',
        ['class' => 'form-control','placeholder' => 'Pass', 'label' =>
        ['class' => 'sr-only', 'text' => 'Pass']]) ?>
        <?= $this->Form->input('role', ['class' => 'form-control', 'placeholder' => 'Role', 'label' =>
        	['class' => 'sr-only', 'text' => 'Role'],
            'options' => ['admin' => 'Admin', 'revisitas' => 'Revisitas', 'user' => 'User']
        ]) ?>
   </fieldset>
<?= $this->Form->button(__('Guardar'), ['class' => 'btn btn-lg btn-primary btn-block']); ?>
<?= $this->Form->end() ?>
</div>
