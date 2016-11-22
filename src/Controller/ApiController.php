<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\BadRequestException;

class ApiController extends AppController
{

	public function initialize(){
		parent::initialize();
	}

	public function beforeFilter(Event $event){
		parent::beforeFilter($event);

		if( ! $this->request->is('ajax') ){
			throw new NotFoundException();
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