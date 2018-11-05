<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class VisitasTable extends Table
{
    public function initialize(array $config){
    	$this->belongsTo(
    		'Timbres', [
    			'foreignKey' => 'timbreid'
			]
		);

        $this->hasOne('Users', ['bindingKey' => 'userid', 'foreignKey' => 'id']);

		$this->addBehavior('CounterCache', [
            'Timbres' => ['visitas_count']
        ]);
    }
}