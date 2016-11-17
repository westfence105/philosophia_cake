<?php
namespace App\Model\Validation;

use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

use App\Model\Table\NamesTable;

class NameValidator extends Validator {

	public static function require( Validator &$validator, string $field, string $message = null, $when = null ){
		$validator->requirePresence( $field, $when, $message )
				  ->notEmpty( $field, $message, $when );
		return $validator;
	}

	public function __construct(){
		parent::__construct();

		$this->requirePresence(['username', 'type', 'display'])
			 ->notEmpty(['username','type','display'])
			 ->inList('type', array_keys( NamesTable::types() ) )
			 ->inList('display', array_keys( NamesTable::display() ) )
			;

		self::require( $this, 'name', __('"Name" is required except "Display" is "Short".'),
				function($context){
					return ( (!array_key_exists('display', $context['data'])) ||
							  $context['data']['display'] != 'short' );
				}
		  );
		self::require( $this, 'short', __('"Short" required when "Display" is "Short".'),
				function($context){
					return ( array_key_exists('display', $context['data']) && 
							  $context['data']['display'] == 'short' );
				} 
		  );

	}
}