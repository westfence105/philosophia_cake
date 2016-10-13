<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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
        $this->hasMany('ProfileData',[ 'foreignKey' => 'username' ]);
    }

    public function isValidUsername($str){
        return preg_match( '/^[_A-Za-z0-9]*$/', $str ) == 1;
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator->requirePresence([ 'username', 'password' ])
                  ->notEmpty( 'username', __('Username is empty.') )
                  ->notEmpty( 'password', __('Password is empty.') )
                  ->add( 'username', [
                            'unique' => [
                                'rule' => 'validateUnique',
                                'provider' => 'table',
                                'message' => __('Username already exists.')
                            ] 
                         ] )
                  ->add( 'username', [
                            'length'=> [
                                'rule' => [ 'lengthBetween', 4, 16 ],
                                'message' => __('Username have to be between 4 and 16 characters.')
                            ]
                         ] )
                  ->add( 'password', [
                            'length' => [
                                'rule' => [ 'minLength', 8 ], 
                                'message' => __('Password have to be over 8 characters.')
                            ]
                         ] )
                  ->add( 'username', 'custom', [
                            'rule' => [ $this, 'isValidUsername' ],
                            'message' => __('Username can only contain letters, numbers or underscore(_).')
                         ] )
                  ->ascii( 'password', __('Password can only contain ASCII characters.') )
            ;

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
}
