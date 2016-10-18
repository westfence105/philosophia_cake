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
    const DISPLAY = [ 'private' => 0, 'omit' => 1, 'short' => 2, 'display' => 3  ];

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

        $validator->requirePresence([
                'username', 'order_key', 'name', 'type', 'display'
            ]);

        $validator
            ->notEmpty('username');

        $validator
            ->numeric('order_key')
            ->integer('order_key')
            ->notEmpty('order_key');

        $validator
            ->notEmpty('name');

        $validator
            ->notEmpty('type');

        $validator
            ->numeric('display')
            ->integer('display')
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
        return $rules;
    }
}
