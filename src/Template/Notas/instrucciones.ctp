<?php
  $this->Html->css('dashboard', ['block' => true]);
  $this->extend('/Layout/admin');
  //$this->Html->css('air', ['block' => true]);
?>

<?= $this->Form->create($nota, ['class' => 'form-horizontal']) ?>
<div class="row">
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="row">
        <div class="col-md-offset-8 col-offset-xs-2 col-sm-offset-4 col-md-1 col-xs-2 col-sm-2">
          <a class="btn btn-md" target="_blank" href="http://markdown.es/sintaxis-markdown/">Sintaxis</a>
        </div>
        <div class="col-md-3 col-sm-5 col-xs-8">
          <button type="button" id="edit" class="btn btn-md"><span class="glyphicon glyphicon-pencil"></span></button>
          <button type="submit" id="save" class="btn btn-md btn-success" style="display:none;">Guardar</button>
          <button type="button" id="cancel" class="btn btn-md btn-danger" style="display:none;"><span class="glyphicon glyphicon-remove"></span></button>
        </div>
      </div>
    </div>
    <div class="panel-body">
      <div id="wrapper" class="col-md-12">
        <figure class="markdown" style="">
          <?php if($nota){ echo $this->Markdown->toHtml($nota->texto); } ?>
        </figure>
        <?= $this->Form->textarea('texto',
          ['id' => 'texto', 'rows'=> 100, 'cols' => 90]) ?>
      </div>
    </div>
  </div>

</div>
<?= $this->Form->end() ?>

<script>
($(document).ready(function(){
  $('#edit').click(function(){
    $('#wrapper figure').hide();
    $('#texto').show();
    $('#save').show();
    $('#cancel').show();
  });
  $('#cancel').click(function(){
    $('#wrapper figure').show();
    $('#texto').hide();
    $('#save').hide();
    $(this).hide();
  });
}));
</script>
<style>
textarea {
    border: none;
    max-width: 100%;
    overflow: auto;
    outline: none;
    font-size:1.5em;
    display:none;
    -webkit-box-shadow: none;
    -moz-box-shadow: none;
    box-shadow: none;
    resize: vertical;
}
</style>
