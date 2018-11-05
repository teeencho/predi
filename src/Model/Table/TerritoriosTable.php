<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Territorios Model
 *
 * @method \App\Model\Entity\Territorio get($primaryKey, $options = [])
 * @method \App\Model\Entity\Territorio newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Territorio[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Territorio|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Territorio patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Territorio[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Territorio findOrCreate($search, callable $callback = null, $options = [])
 */
class TerritoriosTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('territorios');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('file');

        $validator
            ->scalar('nombre')
            ->allowEmpty('nombre');

        return $validator;
    }
}
