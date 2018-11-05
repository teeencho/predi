<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class MarkersTable extends Table
{
  public function initialize(array $config){
    $this->hasOne('Revisita', ['foreignKey' => 'revisita']);
  }
}
