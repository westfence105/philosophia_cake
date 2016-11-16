<?php
namespace App\Controller;

use App\Controller\ApiController;

use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\BadRequestException;

class NamesController extends ApiController
{
	public function initialize(){
		parent::initialize();

		$this->loadModel('Names');
	}

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

	public function edit( $lang ){
		$data_in = $this->request->data();
		//do validate and update
		return $this->serialize($data_in);
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