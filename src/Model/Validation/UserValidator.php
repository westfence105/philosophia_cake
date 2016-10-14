<?php
namespace App\Model\Validation;

use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Datasource\Exception\RecordNotFoundException;

class UserValidator extends Validator {
	public function __construct(){
		parent::__construct();

		$this->requirePresence([ 'username', 'password' ])
             ->notEmpty( 'username', __('Username is empty.') )
             ->notEmpty( 'password', __('Password is empty.') )
             ->add( 'username', [
                    	'unique' => [
                        	'rule' => function ($value){
                        			$users = TableRegistry::get('Users');
                        			try{
                        				$users->get($value);
                        				return false;
                        			}
                        			catch( RecordNotFoundException $e ){
                        				return true;
                        			}
                        		},
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
             ->add( 'username', 'valid_characters', [
                        'rule' => function ( $value ) {
                            	return preg_match('/^[_A-Za-z0-9]*$/', $value ) == 1;
                        	},
                        'message' => __('Username can only contain letters, numbers or underscore(_).')
                    ] )
             ->ascii( 'password', __('Password can only contain ASCII characters.') )
             ->allowEmpty('language')
             ->add( 'language', 'language_code', [
                        'rule' => function ($value) {
                            	return preg_match('/^[a-z]{2,3}([-_][A-Za-z]{2}){0,1}$/', $value ) == 1;
                            },
                        'message' => __("Please input ISO 639 language code (ex. 'en', 'en_US', 'eng')")
                    ] )
            ;
	}
}

?>