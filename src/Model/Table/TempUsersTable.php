<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Cake\Event\Event;
use ArrayObject;

/**
 * TempUsers Model
 *
 * @method \App\Model\Entity\TempUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\TempUser newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TempUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TempUser|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TempUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TempUser[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TempUser findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TempUsersTable extends Table
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

        $this->table('temp_users');
        $this->displayField('token');
        $this->primaryKey('token');

        $this->addBehavior('Timestamp');

        $this->_validatorClass = 'App\Model\Validation\UserValidator';
    }

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options ){
        if( empty($data['token']) ){
            $data['token'] = sha1(uniqid(rand(),true));
        }
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
            ->requirePresence('username', 'create')
            ->notEmpty('username');

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password');

        $validator
            ->allowEmpty('language');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->requirePresence('token', 'create')
            ->notEmpty('token');

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
        $rules->add( $rules->isUnique(['token', 'username', 'email']) );

        return $rules;
    }
}
