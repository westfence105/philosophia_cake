<?php
namespace App\Model\Validation;

use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

use App\Model\Table\NamesTable;

class NameValidator extends Validator {

	public static function _require( Validator &$validator, string $field, string $message = null, $when = null ){
		$validator->requirePresence( $field, $when, $message )
				  ->notEmpty( $field, $message, $when );
		return $validator;
	}

	public function __construct(){
		parent::__construct();

		$display_list = [];
		foreach ( NamesTable::display() as $display => $desc ) {
			$display_list[] = $display;
			$display_list[] = NamesTable::DISPLAY[$display];
		}

		$this->requirePresence(['type', 'display'])
			 ->notEmpty(['type','display'])
			 ->inList('type', array_keys( NamesTable::types() ) )
			 ->inList('display', $display_list )
			;

		self::_require( $this, 'name', __('"Name" is required except "Display" is "Short".'),
				function($context){
					return ( (!array_key_exists('display', $context['data'])) ||
							  $context['data']['display'] != 'short' );
				}
		  );
		self::_require( $this, 'short', __('"Short" required when "Display" is "Short".'),
				function($context){
					return ( array_key_exists('display', $context['data']) && 
							  $context['data']['display'] == 'short' );
				} 
		  );

	}
}