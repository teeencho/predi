<?php
  $this->Html->css('dashboard', ['block' => true]);
?>
<div class="col-sm-3 col-md-2 col-lg-2 sidebar">
  <ul class="nav nav-sidebar">
    <li><?= $this->Html->link('Edificios', ['controller' => 'edificios', 'action' => 'index']) ?></li>
    <li><?= $this->Html->link('Visitas', ['controller' => 'visitas', 'action' => 'index']) ?></li>
    <li><?= $this->Html->link('Llamadas', ['controller' => 'llamadas', 'action' => 'index']) ?></li>
    <li><?= $this->Html->link('Revisitas', ['controller' => 'revisitas', 'action' => 'index']) ?></li>
    <li><?= $this->Html->link('Estadísticas', ['controller' => 'edificios','action' => 'estadisticas']) ?></li>
    <li><?= $this->Html->link('Usuarios', ['controller' => 'users','action' => 'index']) ?></li>
    <li><?= $this->Html->link('Instrucciones', ['controller' => 'notas','action' => 'instrucciones']) ?></li>
  </ul>
</div>
<div class="col-sm-9 col-sm-offset-3 col-md-offset-2 col-md-10 col-lg-10 col-lg-offset-2 main">
  	<?= $this->Flash->render() ?>
	<?= $this->fetch('content'); ?>
</div>
