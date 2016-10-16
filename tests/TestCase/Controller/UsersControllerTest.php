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

    public function setUp(){
        parent::setUp();


    }

    public function testUnauthenticatedFails(){
        $this->get([ 'controller' => 'Users', 'action' => 'index' ]);
        $this->assertRedirect([ 'controller' => 'Pages', 'action' => 'introduction' ]);
    }

    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testHome()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testRegister()
    {
        $auth_data = [
                'username' => 'test',
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

        //test register
        $this->enableCsrfToken();
        $this->post([ 'controller' => 'Users', 'action' => 'register' ], $auth_data );
        $this->assertResponseOk('failed to add user',$this->_response);

        $response = $http->get('http://localhost:1080/messages/1.plain');
        $this->assertTrue( $response->isOk(), 'failed to get catched mail');
        $this->assertTrue( preg_match( '/^\/register\?token=.*$/m', $response->body, $matches ) == 1, $response->body );
        $this->get( $matches[0] );
        $this->assertRedirect( '/', $matches[0]."\n".$this->_response );

        //test login
        $this->post([ 'controller' => 'Users', 'action' => 'login' ], $auth_data );
        $this->assertRedirect('/', $this->_response );

        //set session
        $this->session([ 'Auth.User.username' => $auth_data['username'] ]);

        //test profile
        $this->get('/users/test');
        $this->assertResponseOk('failed to access profile page');

        $this->get('/users');
        $this->assertResponseCode( 404, 'response of request with no user' );

        $this->get('/users/not_exist');
        $this->assertResponseCode( 404, 'response of request for not exist user' );
    }

}
