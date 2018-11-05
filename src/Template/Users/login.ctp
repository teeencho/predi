<?php
	$this->Html->css('signin', ['block' => true]);
?>

<?= $this->Flash->render('auth') ?>
<?= $this->Form->create('User',['class' => 'form-signin']) ?>
    <fieldset>
        <legend><?= __('Por favor ingrese usuario y contraseña') ?></legend>
        <?= $this->Form->input('username',
        ['class' => 'form-control','placeholder' => 'User', 'label' =>
        ['class' => 'sr-only', 'text' => 'User']]) ?>
        <?= $this->Form->input('password',
        ['class' => 'form-control','placeholder' => 'Pass', 'label' =>
        ['class' => 'sr-only', 'text' => 'Pass']]) ?>
    </fieldset>
<?= $this->Form->button(__('Login'), ['class' => 'btn btn-lg btn-primary btn-block']); ?>
<?= $this->Form->end() ?>
