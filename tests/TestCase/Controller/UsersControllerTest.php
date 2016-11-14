<?php
namespace App\Test\TestCase\Controller;

use App\Controller\UsersController;
use Cake\TestSuite\IntegrationTestCase;

use Cake\Http\Client;
use Cake\Mailer\Email;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * App\Controller\UsersController Test Case
 */
class UsersControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users',
        'app.temp_users',
        'app.names'
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

    public function testCsrf(){
        $this->post([ 'controller' => 'Users', 'action' => 'login' ], $this->auth_data );
        $this->assertResponseError();

        $this->post([ 'controller' => 'Users', 'action' => 'login' ], $this->new_user );
        $this->assertResponseError();
    }

    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testHome()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testLogin(){
        $this->enableCsrfToken();
        $this->post([ 'controller' => 'Users', 'action' => 'login' ], $this->auth_data );
        $this->assertRedirect('/', $this->_response );
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
        $this->post([ 'controller' => 'Users', 'action' => 'register' ], $this->new_user );
        $this->assertResponseOk('failed to add user',$this->_response);

        //get verify email and access link (to complete register)
        $response = $http->get('http://localhost:1080/messages/1.plain');
        $this->assertTrue( $response->isOk(), 'failed to get catched mail');
        $this->assertTrue( preg_match( '/^\/register\?token=.*$/m', $response->body, $matches ) == 1, $response->body );
        $this->get( $matches[0] );
        $this->assertRedirect( '/home', $matches[0]."\n".$this->_response );
    }

    public function testProfile() {
        $this->session([ 'Auth.User.username' => $this->auth_data['username'] ]);
        $this->enableCsrfToken();

        $this->get('/users/user');
        $this->assertResponseOk('failed to access profile page');

        $this->get('/users');
        $this->assertResponseCode( 404, 'response of request with no user' );

        $this->get('/users/not_exist');
        $this->assertResponseCode( 404, 'response of request for not exist user' );
    }

    public function testSettings() {
        $this->markTestIncomplete('Not implemented yet.');
    }

}
