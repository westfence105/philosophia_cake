<?php
namespace App\Controller;

use App\Controller\AppController;

class PagesController extends AppController
{
	public function initialize(){
		parent::initialize();

		$this->Auth->allow([ 'index' ]);
	}

	public function index(){
		if( $this->Auth->user() ){
			//logged in

		}
		else {
			//not logged in

			$this->render('introduction');
		}
	}
}
?>