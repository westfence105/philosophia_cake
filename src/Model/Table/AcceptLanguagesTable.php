<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AcceptLanguages Model
 *
 * @method \App\Model\Entity\AcceptLanguage get($primaryKey, $options = [])
 * @method \App\Model\Entity\AcceptLanguage newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AcceptLanguage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AcceptLanguage|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AcceptLanguage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AcceptLanguage[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AcceptLanguage findOrCreate($search, callable $callback = null)
 */
class AcceptLanguagesTable extends Table
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

        $this->table('accept_languages');
        $this->displayField('id');
        $this->primaryKey('id');

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
            ->requirePresence('language', 'create')
            ->notEmpty('language');

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
