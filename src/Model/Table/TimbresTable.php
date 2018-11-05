<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class TimbresTable extends Table
{
    public function initialize(array $config){
		$this->hasMany(
			'Visitas',[
				'foreignKey' => 'timbreid',
				'order' => 'id ASC'
			]
		);

		$this->belongsTo('Edificios',['foreignKey' => 'edificioid']);
    }
}