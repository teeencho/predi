<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Predi</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <?php if ($this->request->session()->check('Auth.User.id')): ?>
          <ul class="nav navbar-nav navbar-right">
            <li><?= $this->Html->link('Visitar', ['controller' => 'edificios', 'action' => 'visitar'])  ?></li>
            <?php if ($this->request->session()->read('Auth.User.role') == 'admin'): ?>
              <li><?= $this->Html->link('Administración', ['controller' => 'edificios', 'action' => 'index'])  ?></li>
            <?php endif; ?>
            <li><?= $this->Html->link('Cerrar sesión', array('controller' => 'users', 'action' => 'logout')) ?></li>
          </ul>
        <?php endif; ?>
        </div>
      </div>
    </nav>
