<?php
namespace App\Controller;

use App\Controller\AppController;

use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\I18n;
use Cake\Mailer\MailerAwareTrait;
use Cake\Error\Debugger;

use App\Model\Table\NamesTable;

use Cake\Network\Exception\HttpException;
use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Datasource\Exception\RecordNotFoundException;

class UsersController extends AppController
{
    use MailerAwareTrait;

    public function initialize(){
        parent::initialize();

        $this->Auth->allow([ 'login', 'register' ]);
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

    public function home(){

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

    public function profile( $username ){
        try {
            $user = $this->Users->get($username);

            $this->set('profile',[
                    'username' => $username,
                ]);
        }
        catch( RecordNotFoundException $e ) {
            throw new NotFoundException();
        }
    }
}

?>