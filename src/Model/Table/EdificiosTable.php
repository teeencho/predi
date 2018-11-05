<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class EdificiosTable extends Table
{

	public function initialize(array $config){
		$this->hasMany('Timbres', ['foreignKey' => 'edificioid']);
	}

  public function validationDefault(Validator $validator)
  {
      return $validator
          ->notEmpty('calle', 'Calle es requerido')
          ->notEmpty('altura', 'Altura es requerido');
  }
}
