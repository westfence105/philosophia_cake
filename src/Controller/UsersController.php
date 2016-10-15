<?php
namespace App\Controller;

use App\Controller\AppController;

use Cake\ORM\TableRegistry;
use Cake\I18n\I18n;
use Cake\Mailer\MailerAwareTrait;

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
        $this->set('username',$this->Auth->user('username'));
    }

    public function home(){

    }

    public function login()
    {
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
        $this->set('language', I18n::locale() );
        $this->set( 'entities', null );
        $token = $this->request->query('token');
        if( $token ){
            $temp_users = TableRegistry::get('TempUsers');
            
            $cols = ['username', 'password', 'email', 'language'];
            $select = $temp_users
                        ->find()
                        ->select($cols)
                        ->where([ 'token' => $token ]);
            $ret =  $this->Users->query()
                        ->insert($cols)
                        ->values($select)
                        ->execute();
            if( $ret ){
                $this->Auth->setUser(['username' => $select->first()->username ]);
                return $this->redirect( $this->Auth->redirectUrl() );
            }
            else {
                $this->Flash->error(__('Failed to add to database.') );
                $this->set('entities', $user );
            }
        }
        else if ($this->request->is('post') ){
            $temp_users = TableRegistry::get('TempUsers');
            $user = $temp_users->newEntity( $this->request->data );
    
            if( $user->errors() ){
                $this->Flash->error( __('Form data has invalid content.') );
            }
            else if( $temp_users->save($user) ){
                debug($user);
                $this->Flash->success( __x('registration completed','Success') );
                $this->getMailer('User')->send('verify_email',[$user]);
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
            $this->set('username',$username);
        }
        catch( RecordNotFoundException $e ) {
            throw new NotFoundException();
        }
    }
}

?>