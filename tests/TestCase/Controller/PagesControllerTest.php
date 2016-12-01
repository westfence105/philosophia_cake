<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         1.2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Test\TestCase\Controller;

use App\Controller\PagesController;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\TestSuite\IntegrationTestCase;
use Cake\View\Exception\MissingTemplateException;

use Cake\Log\Log;
use Cake\Http\Client;
use Cake\Mailer\Email;

/**
 * PagesControllerTest class
 */
class PagesControllerTest extends IntegrationTestCase
{
    public $fixtures = [
        'app.users',
        'app.temp_users',
    ];

    public $auth_data = [
                'username' => 'user',
                'password' => 'password'
            ];

    public $new_user = [
                'username' => 'user2',
                'password' => 'password',
                'language' => 'en_US',
                'email' => 'test@example.com'
            ];

    public function setUp(){
        parent::setUp();

        $this->enableSecurityToken();
        $this->configRequest([
                'environment' => [ 'HTTPS' => 'on' ]
            ]);
    }

    protected function setUser(){
        $this->session([ 'Auth.User.username' => 'user' ]);
        $this->enableCsrfToken();
    }

    public function testIndex()
    {
        $this->enableCsrfToken();
        $this->get('/');
        $this->assertResponseOk();
    }

    public function testHome()
    {
        $this->setUser();
        $this->get('/');
        $this->assertResponseOk();
    }

    public function testLogin(){
        $auth_data = [
            'username' => 'user',
            'password' => 'password',
        ];

        $this->get('/login');
        $this->assertResponseCode(403);
        $this->post('/login', $auth_data );
        $this->assertResponseCode(403);

        $this->enableCsrfToken();

        $this->get('/login');
        $this->assertResponseOk();
        $this->post('/login', $auth_data );
        $this->assertRedirect('/');
    }

    public function testLogout(){
        $this->setUser();
        $this->get('/logout');
        $this->assertRedirect('/login');
    }

    public function testSettings(){
        $this->setUser();
        $this->get('/settings');
        $this->assertResponseOk();
    }

    public function testProfile() {
        $this->setUser();

        $this->get('/users/user');
        $this->assertResponseOk('failed to access profile page');

        $this->get('/users');
        $this->assertResponseCode( 404, 'response of request with no user' );

        $this->get('/users/not_exist');
        $this->assertResponseCode( 404, 'response of request for not exist user' );
    }

    public function testRegister()
    {
        //setup email
        Email::dropTransport('default');
        Email::configTransport( 'default', [
                'host' => 'localhost',
                'port' => 1025,
                'className' => 'Smtp'
            ] );

        //setup http client (for getting email)
        $http = new Client();
        $http->delete('http://localhost:1080/messages');

        $this->enableCsrfToken();
        $this->post( '/register', $this->new_user );
        $this->assertResponseOk('failed to add user',$this->_response);

        //get verify email and access link (to complete register)
        $response = $http->get('http://localhost:1080/messages/1.plain');
        $this->assertTrue( $response->isOk(), 'failed to get catched mail');
        $this->assertTrue( preg_match( '/^\/register\?token=.*$/m', $response->body, $matches ) == 1, $response->body );
        $this->get( $matches[0] );
        $this->assertRedirect( '/home', $matches[0]."\n".$this->_response );
    }


    /**
     * Test that missing template renders 404 page in production
     *
     * @return void
     */
    public function testMissingMethod()
    {
        $this->setUser();
        Configure::write('debug', false);
        $this->get('/not_existing');

        $this->assertResponseError();
        $this->assertResponseContains('Error');
    }

    /**
     * Test that missing template in debug mode renders missing_template error page
     *
     * @return void
     */
    public function testMissingMethodInDebug()
    {
        $this->setUser();
        Configure::write('debug', true);
        $this->get('/not_existing');

        $this->assertResponseError();
        $this->assertResponseContains('Missing Method');
        $this->assertResponseContains('Stacktrace');
        $this->assertResponseContains('missing_action.ctp');
    }
}
