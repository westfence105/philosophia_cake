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
        'app.temp_users'
    ];

    public $auth_data = [
            'username' => 'users_test',
            'password' => 'password'
        ];

    public function setUp(){
        parent::setUp();

        $this->enableCsrfToken();
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
        $this->post([ 'controller' => 'Users', 'action' => 'login' ], $this->auth_data );
        $this->assertRedirect('/', $this->_response );
    }

    public function testRegister()
    {
        $user_data = [
                'username' => 'test2',
                'password' => 'password',
                'language' => 'en_US',
                'email' => 'test@example.com'
            ];

        Email::dropTransport('default');
        Email::configTransport( 'default', [
                'host' => 'localhost',
                'port' => 1025,
                'className' => 'Smtp'
            ] );

        $http = new Client();
        $http->delete('http://localhost:1080/messages');

        $this->post([ 'controller' => 'Users', 'action' => 'register' ], $user_data );
        $this->assertResponseOk('failed to add user',$this->_response);

        $response = $http->get('http://localhost:1080/messages/1.plain');
        $this->assertTrue( $response->isOk(), 'failed to get catched mail');
        $this->assertTrue( preg_match( '/^\/register\?token=.*$/m', $response->body, $matches ) == 1, $response->body );
        $this->get( $matches[0] );
        $this->assertRedirect( '/home', $matches[0]."\n".$this->_response );
    }

    public function testProfile() {
        $this->session([ 'Auth.User.username' => $this->auth_data['username'] ]);

        $this->get('/users/users_test');
        $this->assertResponseOk('failed to access profile page');

        $this->get('/users');
        $this->assertResponseCode( 404, 'response of request with no user' );

        $this->get('/users/not_exist');
        $this->assertResponseCode( 404, 'response of request for not exist user' );
    }

}
