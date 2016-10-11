<?php
namespace App\Controller;

use App\Controller\AppController;

class PagesController extends AppController
{
	public function initialize(){
		parent::initialize();

		$this->Auth->allow([ 'introduction' ]);
	}

	public function introduction(){

	}
}
?>