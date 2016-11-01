<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

use Cake\I18n\I18n;

use Cake\ORM\TableRegistry;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Csrf');
        $this->loadComponent('Auth', 
                    [
                        'authenticate' => 'Form' ,
                        'loginAction' => ['controller' => 'Users', 'action' => 'login'],
                        'loginRedirect' => ['controller' => 'Pages', 'action' => 'index'],
                    ]);
        $this->Auth->config('checkAuthIn', 'Controller.initialize');
        $this->loadComponent('Security', [
                                'blackHoleCallback' => 'redirectSSL',
                                'validatePost' => false,
                            ]);
    }

    public function redirectSSL( $type ){
        if('secure'){
            $this->redirect('https://'.env('SERVER_NAME').$this->request->here);
        }
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

    public function beforeFilter(Event $event){
        $this->Security->requireSecure();
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
}
