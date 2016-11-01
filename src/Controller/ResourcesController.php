<?php
namespace App\Controller;

use App\Controller\AppController;

use Cake\I18n\I18n;
use App\Model\Table\NamesTable;

use Cake\Network\Exception\NotFoundException;

class ResourcesController extends AppController
{
	public function initialize(){
		parent::initialize();
	}

	public function languageName(){
		$this->autoRender = false;
		if( $lang = $this->request->query('lang') ){
			echo \Locale::getDisplayName( $lang, $lang );
		}
	}
}