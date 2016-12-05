<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Cake\I18n\I18n;

/**
 * Users Model
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null)
 */
class UsersTable extends Table
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

        $this->table('users');
        $this->displayField('username');
        $this->primaryKey('username');

        $this->hasMany('Names', ['foreignKey' => 'username']);
        $this->hasMany('AcceptLanguages', ['foreignKey' => 'username']);
        $this->hasMany('ProfileData', ['foreignKey' => 'username']);
        $this->hasMany('Documents', ['foreignKey' => 'username']);

        $this->_validatorClass = 'App\Model\Validation\UserValidator';
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
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
        $rules->addCreate( $rules->isUnique(['username']), __('Username already exists.') );

        return $rules;
    }

    public function getAcceptLanguages( string $username ){
        $user = $this->get($username);
        $ret[] = $user->language;

        $query = $this->AcceptLanguages->find()
                                       ->where(['username' => $username ])
                                       ->order(['order_key' => 'ASC'])
                                    ;
        foreach( $query as $entity ){
            $ret[] = $entity->language;
        }

        return $ret;
    }

}
