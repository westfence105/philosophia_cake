<?php
namespace App\Test\TestCase\Model\Validation;

use Cake\TestSuite\TestCase;

use App\Model\Table\NamesTable;
use App\Model\Validation\NameValidator;

class NameValidatorTest extends TestCase
{
	public function testValidate(){
		$base = [
			'username' => 'smith', 
			'name' => 'John',
			'type' => 'given',
			'display' => 'display',
			'short' => 'J.',
		];

		$valid_args = [];
		foreach( NamesTable::types() as $type => $type_name ){
			foreach ( NamesTable::display() as $display => $display_str ) {
				$valid_args[] = array_merge( $base, ['type' => $type, 'display' => $display ]);
				if( $display == 'short' ){
					$args = array_merge( $base, ['name' => '', 'type' => $type, 'display' => 'short' ]);
					$valid_args[] = $args;

					unset($args['name']);
					$valid_args[] = $args;
				}
				else {
					$args = array_merge( $base, ['type' => $type, 'display' => $display, 'short' => '' ]);
					$valid_args[] = $args;

					unset($args['short']);
					$valid_args[] = $args;
				}
			}
		}

		$invalid_args = [
			array_merge( $base, ['name' => '', 'display' => 'display' ]), //empty name
			array_merge( $base, ['name' => '', 'display' => 'omit'    ]), //empty name
			array_merge( $base, ['name' => '', 'display' => 'private' ]), //empty name
			array_merge( $base, ['display' => 'short',  'short' => '' ]), //empty name
			array_merge( $base, ['type' => 'giben']), 					  //invalid type
			array_merge( $base, ['display' => 'dispray']), 				  //invalid display
		];

		$required = ['username','name','type','display','short'];
		foreach ( $required as $i => $value ) {
			$arg = $base;
			unset($arg[$value]);
			if( $value == 'short' ){
				$arg['display'] = 'short';
			}
			$invalid_args[] = $arg;
		}


		$validator = new NameValidator();

		foreach( $valid_args as $i => $args ){
			$errors = $validator->errors( $args );
			$this->assertEmpty( $errors, json_encode($args)."\n".json_encode($errors) );
			unset($errors);
		}
		unset($args);

		foreach( $invalid_args as $i => $args ){
			$errors = $validator->errors( $args );
			$this->assertNotEmpty( $errors, json_encode($args) );
			unset($errors);
		}
		unset($args);
	}
}