<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Names Model
 *
 * @method \App\Model\Entity\Name get($primaryKey, $options = [])
 * @method \App\Model\Entity\Name newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Name[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Name|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Name patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Name[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Name findOrCreate($search, callable $callback = null)
 */
class NamesTable extends Table
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

        $this->table('names');
        $this->displayField('name');
        $this->primaryKey('id');
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
            ->requirePresence('username', 'create')
            ->notEmpty('username');

        $validator
            ->integer('order_key')
            ->requirePresence('order_key', 'create')
            ->notEmpty('order_key');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->integer('display')
            ->requirePresence('display', 'create')
            ->notEmpty('display');

        $validator
            ->allowEmpty('clipped');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['username']));

        return $rules;
    }
}
