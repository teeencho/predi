<?php
  $this->Html->css('dashboard', ['block' => true]);
  $this->extend('/Layout/admin');
?>
  <div class="row">
    <div class="col-md-12">
      <h1 class="page-header">Usuarios</h1>
      <?= $this->Html->link('Nuevo', ['controller' => 'users' ,'action' => 'add'], ['class' => 'btn btn-primary']) ?>
    </div>
  </div>
  <div class="row" style="margin-top:10px;">
    <div class="col-md-12">
      <table class="table table-striped table-condensed">
        <tr><th>User</th><th>Rol</th></tr>
        <?php foreach ($users as $user): ?>
          <tr>
            <td class="col-md-1"><?= strtoupper(h($user->username)) ?></td>
            <td class="col-md-1"><?= strtoupper(h($user->role)) ?></td>
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