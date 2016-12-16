<?php
namespace App\Controller;

use App\Controller\AppController;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\I18n;
use Cake\Mailer\MailerAwareTrait;

use Cake\Network\Exception\NotFoundException;
use Cake\Datasource\Exception\RecordNotFoundException;

use App\Utils\AppUtility;

class PagesController extends AppController
{
    use MailerAwareTrait;

	public function initialize(){
		parent::initialize();

        $this->loadModel('Users');
        $this->loadModel('Names');

		$this->Auth->allow([ 'index', 'login', 'register' ]);
	}

    public function beforeFilter( Event $event ){
        $username = $this->Auth->user('username');
        if( $username ) {
            $users = TableRegistry::get('Users');
            try {
                $user = $users->get( $username );
                $this->set('username', $username );

                $language = $user['language'];
                if( ! empty($language) ){
                    @I18n::locale( $language );
                    $this->set('language', @\Locale::getDisplayLanguage( $language ) );
                }
            }
            catch( RecordNotFoundException $e ){
                $this->redirect( $this->Auth->logout() );
            }
        }
    }

	public function index(){
		if( $this->Auth->user() ){
			//logged in
            return $this->setAction('home');
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
        if( $this->Auth->user() ){
            return $this->redirect( $this->Auth->redirectUrl() );
        }
        $this->set('title', __('Register') );
        $this->set('language', I18n::locale() );
        $this->set('entities', null );
        $this->loadModel('TempUsers');

        $token = $this->request->query('token');
        if( $token ){
            $user = $this->TempUsers->register( $token );
            if( $user && empty( $user->errors() ) ){
                $this->Auth->setUser(['username' => $user->username ]);

                $this->Flash->Success(__('registration completed','Success'));
                return $this->redirect('/home');
            }
            else if( $user ){
                $this->Flash->error(__('Failed to add to database.') );
                $this->set('entities', $user );
            }
            else {
                $this->Flash->error(__('Invalid token given.') );
            }
        }
        else if ($this->request->is('post') ){
            $user = $this->TempUsers->newEntity( $this->request->data );
    
            if( $user->errors() ){
                $this->Flash->error( __('Form data has invalid content.') );
            }
            else if( $this->TempUsers->save($user) ){
                $this->getMailer('User')->send('verify_email',[$user]);
                $this->set('email',$user->email);
                $this->render('register_verify');
            }
            else{
                $this->Flash->error( __('Failed to add to database.') );
            }

            $this->set( 'entities', $user );
        }
    }

	public function settings(){
        $this->set('title',__x('title of user settings page','Settings'));
        $username = $this->Auth->user('username');
	}

    public function profile( $username ){
        try {
            $profile = [
                'username' => $username,
            ];

            $user = $this->Users->get($username);

            //set names
            $accept  = $this->Users->getAcceptLanguages( $this->Auth->user('username') );
            if(!array_filter($accept) ){
                if( $header_str = $this->request->env('HTTP_ACCEPT_LANGUAGE') ){
                    $accept = AppUtility::parseAcceptLanguage( $header_str );
                }
            }
            $presets = AppUtility::sortPresets( $this->Names->getPresets( $username ), $accept );

            $names = [];
            foreach( $presets as $i => $preset ){
                $names[$preset] = $this->Names->getName( $username, $preset, ['display_level' => 'full']);
            }
            $profile['names'] = $names;

            $profile['languages'] = $this->Users->getAcceptLanguages( $username );

            $this->set('profile', $profile );
        }
        catch( RecordNotFoundException $e ) {
            throw new NotFoundException();
        }
    }
}
?>