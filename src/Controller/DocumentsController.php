<?php
namespace App\Controller;

use App\Controller\ApiController;

class DocumentsController extends ApiController
{
	public function initialize(){
		parent::initialize();

		$this->loadModel('Documents');
	}

	public function index(){
		
	}

	public function view( $id ){
		$this->set('id',$id);
		$this->set('_serialize',['id']);
	}
}