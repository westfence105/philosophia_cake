<?php
namespace App\Controller;

use App\Controller\ApiController;

use Cake\Log\Log;

use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InternalErrorException;

use App\Model\Validation\NameValidator;

class NamesController extends ApiController
{
	public function initialize(){
		parent::initialize();

		$this->loadModel('Names');
	}

	//GET: /api/1.0/users/names.json
	//format: { <preset>:[<names>], ... }
	public function index(){
		return $this->serialize(
					$this->Names->getNameData( $this->Auth->user('username'), ['display' => 'string'] )
				);
	}

	public function view( $lang ){
		$username = $this->Auth->user('username');
		if( $this->Names->hasPreset( $username, $lang ) ){
			$data = $this->Names->getNameData( $username, ['display' => 'string', 'preset' => $lang ]);
			return $this->serialize( $data[$lang] );
		}
		else{
			throw new NotFoundException();
		}
	}

	//PUT: /api/1.0/users/names/<old_preset>.json (<old_preset> => $lang)
	//format: {"preset":<(new)preset>, "names":[<names>]}
	//errors:
	// {
	//   "errors": { 
	//      "<index>": {
	//         "<field>": {
	//            "<rule>": "<message>",
	//			  ...
    //         },
    //		   ...
    //      },
    //      ...
	//	 }
	// }
	public function edit( $lang ){
		$data = $this->request->data();
		
		if( ! array_key_exists('names', $data ) ){
			throw new BadRequestException('Illegal format. Expected key "names".');
		}

		$names = $data['names'];
		$validator = new NameValidator();
		$errors = [];
		foreach ( $names as $i => $name ) {
			if( is_array( $name ) ){
				$err = $validator->errors( $name );
				if( !empty($err) ){
					$errors[$i] = $err;
				}
			}
			else {
				$errors[$i] = 'Not Array';
			}
		}
		if( ! empty($errors) ){
			$this->response->statusCode(400);
			return $this->serialize(['errors' => (object)$errors ]);
		}
		
		$name_data = [ $lang => $names ];
		$ret = $this->Names->setNameData( 
					$this->Auth->user('username'), $name_data, ['display' => 'string'], $errors );

		if( ! $ret ){
			$this->response->statusCode(500);
			return $this->serialize([
						'message' => 'Unable to save name data.',
						'errors' => (object)$errors,
					]);
		}
		else if( array_key_exists( $lang, $ret ) ){
			$this->set( 'names', $ret[$lang] );
		}
		else {
			throw new InternalErrorException('Unable to find preset from returning value of setNameData.');
		}

		if( array_key_exists('preset', $data ) && $lang != $data['preset'] ){
			if( $this->Names->renamePreset( $lang, $data['preset'] ) === false ){
				$this->set('preset', $data['preset'] );
			}
			else {
				throw new InternalErrorException('Failed to rename preset.');
			}
		}
		else {
			$this->set('preset', $lang );
		}
		
		$this->set('_serialize',['preset','names']);
	}

	public function delete( $lang ){
		$username = $this->Auth->user('username');
		if( $this->Names->hasPreset( $username, $lang ) ){
			$this->Names->removePreset( $username, $lang );
			echo '';
		}
		else{
			throw new NotFoundException();
		}
	}
}