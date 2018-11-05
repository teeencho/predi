<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class RevisitasTable extends Table
{

  public function initialize(array $config){
    $this->hasOne('Markers', ['foreignKey' => 'revisita']);
  }

  public function validationDefault(Validator $validator)
  {
      return $validator
          ->notEmpty('nombre', 'El nombre es requerido')
          ->notEmpty('direccion', 'La direccion es requerida');
  }
}
