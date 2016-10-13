<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProfileData Model
 *
 * @method \App\Model\Entity\ProfileData get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProfileData newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProfileData[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProfileData|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProfileData patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProfileData[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProfileData findOrCreate($search, callable $callback = null)
 */
class ProfileDataTable extends Table
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

        $this->table('profile_data');
        $this->belongsTo('Users', [ 'foreignKey' => 'username' ] );
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
            ->notEmpty('username');

        $validator
            ->integer('order_key')
            ->allowEmpty('order_key');

        $validator
            ->allowEmpty('type');

        $validator
            ->allowEmpty('data');

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
    //    $rules->add($rules->isUnique(['username']));

        return $rules;
    }
}
