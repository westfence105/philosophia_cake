<?php
namespace App\Controller;

use App\Controller\AppController;

use Cake\ORM\TableRegistry;
use Cake\I18n\I18n;
use Cake\Mailer\MailerAwareTrait;

use App\Model\Table\NamesTable;

use Cake\Network\Exception\NotFoundException;
use Cake\Network\Exception\BadRequestException;
use Cake\Datasource\Exception\RecordNotFoundException;

class UsersController extends AppController
{
    use MailerAwareTrait;

    public function initialize(){
        parent::initialize();

        $this->Auth->allow([ 'login', 'register' ]);
    }

    public function index(){
        
    }

    public function home(){

    }

    public function login()
    {
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
                return $this->redirect([ 'controller' => 'Users', 'action' => 'home' ]);
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
        $names = TableRegistry::get('Names');
        $data = [];
        if( $this->request->is('post') ){
            $data = $this->request->data();
            //save set data
        }

        if( ! array_key_exists('names', $data ) ){
            $data['names'] = $names->getNameData( $username, ['display' => 'string','array' => true ] );
        }
        $this->set('data',$data);

        $translations = [
                'short' => __x('description of name.short','Short'),
            ];
        $this->set('resources', [
                'data-translations'     => json_encode( $translations ),
                'data-name-types'       => json_encode( NamesTable::types() ),
                'data-name-displaying'  => json_encode( NamesTable::display() ),
                'data-name-display-description' => json_encode( NamesTable::displayDescription() ),
            ]);
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