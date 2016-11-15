<?php
namespace App\Controller;

use App\Controller\ApiController;

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
		$data = $this->Names->getNameData( 
					$this->Auth->user('username'), [
						'display' => 'string',
						'preset' => $lang,
					]
				);
		return $this->serialize( $data[$lang] );
	}

	public function edit( $lang ){

	}

	public function delete( $lang ){

	}
}