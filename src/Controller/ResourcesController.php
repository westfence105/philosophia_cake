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

	public function nameTypes(){
		if( $this->request->is('json') ){
			$keys = [];
			foreach( NamesTable::types() as $key => $value ){
				$this->set($key,$value);
				$keys[] = $key;
			}
			$this->set('_serialize', $keys );
		}
		else {
			throw new NotFoundException();
		}
	}

	public function nameDisplayEnum(){
		if( $this->request->is('json') ){
			$keys = [];
			foreach( NamesTable::display() as $key => $value ){
				$this->set($key,$value);
				$keys[] = $key;
			}
			$this->set('_serialize', $keys );
		}
		else {
			throw new NotFoundException();
		}
	}

	public function nameDisplayDescriptions(){
		if( $this->request->is('json') ){
			$keys = [];
			foreach( NamesTable::displayDescriptions() as $key => $value ){
				$this->set($key,$value);
				$keys[] = $key;
			}
			$this->set('_serialize', $keys );
		}
		else {
			throw new NotFoundException();
		}
	}

	public function messages(){
		if( $lang = $this->request->query('lang') ){
			I18n::locale($lang);
		}
		$data = [
            'save'  => __x('save user setting(s)','Save'),
            'short' => __x('description of name.short','Short'),
            'error_ajax' => __x('error while ajax connection','Internal error occurred.'),
		];
		$this->autoRender = false;
		if( $key = $this->request->query('key') ){
			echo array_key_exists( $key, $data ) ? $data[$key] : '';
		}
		else if( $this->request->is('json') ){
			$keys = [];
			foreach ( $data as $key => $value ) {
				$this->set($key,$value);
				$keys[] = $key;
			}
			$this->set('_serialize',$keys);
			$this->render();
		}
		else {
			echo '';
		}
	}
}