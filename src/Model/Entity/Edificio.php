<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Edificio extends Entity
{

	protected function direccion(){
		return $this->_properties['calle'] . ' ' .
		$this->_properties['altura'];
	}
}
