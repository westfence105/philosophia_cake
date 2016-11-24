<?php
namespace App\Controller;

use App\Controller\AppController;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\I18n;

class PagesController extends AppController
{
	public function initialize(){
		parent::initialize();

		$this->Auth->allow([ 'index', 'login', 'register' ]);
	}

    public function beforeFilter( Event $event ){
        $username = $this->Auth->user('username');
        if( $username ) {
            $users = TableRegistry::get('Users');
            try {
                $user = $users->get( $username );
                $language = $user['language'];
                @I18n::locale( $language );
                $this->set('username', $username );
                $this->set('language', @\Locale::getDisplayLanguage( $language ) );
            }
            catch( RecordNotFoundException $e ){
                $this->redirect( $this->Auth->logout() );
            }
        }
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

	public function home(){
		$this->set('title',__('Home'));
	}

	public function login(){
        if( $this->Auth->user() ){
            return $this->redirect( $this->Auth->redirectUrl() );
        }
        $this->set('title',__('Login'));
        if( $this->request->is('post') ){
            $user = $this->Auth->identify();
            if( $user ){
                $this->Auth->setUser($user);
                return $this->redirect( $this->Auth->redirectUrl() );
            }
            else {
                $this->Flash->error(__('Username or Password is incorrect.') );
            }
        }
	}

    public function logout(){
        return $this->redirect($this->Auth->logout());
    }

    public function register(){

    }

	public function settings(){
        $this->set('title',__x('title of user settings page','Settings'));
        $username = $this->Auth->user('username');
	}
}
?>