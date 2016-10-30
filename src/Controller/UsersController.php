<?php
namespace App\Controller;

use App\Controller\AppController;

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
        $this->Names = TableRegistry::get('Names');
        $data = [];
        if( $this->request->is('ajax') ){
            $data = $this->request->data();
            Debugger::log( $data, 'debug', 4 );
            try {
                if( !array_key_exists('item', $data ) ){
                    throw new BadRequestException('Illegal format: Expected { "item" => "..." }');
                }
                if( $data['item'] == 'name_preset' ){
                    if( !array_key_exists('preset', $data ) ){
                        throw new BadRequestException('Illegal format: Expected { "preset" => "..." }');
                    }
                    if( empty($data['names']) || (!is_array($data['names'])) ){
                        throw new BadRequestException('Illegal format: Expected { "names" => [...] }');
                    }
                    $preset = $data['preset'];
                    $name_data = [ $preset => $data['names'] ];
                    //validate and save
                    $name_data = $this->Names->setNameData( $username, $name_data, ['display' => 'string' ] );
                    if( !$name_data ){
                        throw new BadRequestException("Illegal format: Validation failed.");
                    }
                    Debugger::log($name_data, 'debug', 10);

                    if( array_key_exists('preset_new', $data ) ){
                        //rename preset
                        if( $this->Names->renamePreset( $username, $preset, $data['preset_new'] ) !== false ){
                            $name_data = array_combine( $data['preset_new'], $name_data[$preset] );
                            $preset = $data['preset_new'];
                        }
                        else {
                            throw new InternalErrorException("Failed to rename preset '$preset' to '$preset_new");
                        }
                    }

                    $this->set('preset', $preset );
                    $this->set('names',  $name_data[$preset]);
                    $this->render('/Element/Users/settings/name_preset');
                    $this->viewBuilder()->layout(false);
                    return;
                }
                else {
                    throw new BadRequestException('Illegal format: Unknown "item" given.}');
                }
            }
            catch( HttpException $e ){
                throw $e;
            }
            catch( \Exception $e ){
                $this->set('error',$e->getMessage());
                $this->set('_serialize',['error']);
            }
        }
        else {
            if( ! array_key_exists('names', $data ) ){
                $data['names'] = $this->Names->getNameData( $username, ['display' => 'string','array' => true ] );
            }
            $this->set('data',$data);
    
            $translations = [
                    'save'  => __x('save user setting(s)','Save'),
                    'short' => __x('description of name.short','Short'),
                    'error_ajax' => __x('error while ajax connection','Internal error occurred.'),
                ];
            $this->set('resources', [
                    'data-translations'     => $translations,
                    'data-name-types'       => NamesTable::types(),
                    'data-name-displaying'  => NamesTable::display(),
                    'data-name-display-description' => NamesTable::displayDescription(),
                ]);
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