<?php
namespace App\Controller;

use App\Controller\AppController;

class ApiController extends AppController
{

	public function initialize(){
		parent::initialize();

		$this->autoRender = false;
	}

	public function beforeFilter(){
		parent::beforeFilter();

		if( ! $this->request->is('ajax') ){
			throw new BadRequestException('Request is not Ajax');
		}

		$this->request->header('X-Content-Type-Options','nosniff');
	}

	protected function serialize( array $result ){
		$keys = [];
		foreach( $result as $key => $value ){
			$keys[] = $key;
			$this->set( $key, $value );
		}
		$this->set('_serialize', $keys );
	}
}