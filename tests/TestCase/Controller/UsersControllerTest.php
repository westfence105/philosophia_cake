<?php
namespace App\Test\TestCase\Controller;

use App\Controller\UsersController;
use Cake\TestSuite\IntegrationTestCase;

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
        'app.users'
    ];

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

    public function testLogin()
    {
        $auth_data = [ 'username' => 'test', 'password' => 'password' ];
        $this->enableCsrfToken();
        $this->post([ 'controller' => 'Users', 'action' => 'register' ], $auth_data );
        $this->assertResponseOk('failed to add user');

        $this->post([ 'controller' => 'Users', 'action' => 'login' ], $auth_data );
        $this->assertRedirect('/');
    }

    public function testRegister()
    {
        $auth_data = [ 'username' => 'test', 'password' => 'password' ];
        $this->enableCsrfToken();
        $this->post([ 'controller' => 'Users', 'action' => 'register' ], $auth_data );
        $this->assertResponseOk('failed to add user');
    }

    public function testProfile(){
        $auth_data = [ 'username' => 'test', 'password' => 'password' ];
        $this->enableCsrfToken();
        $this->post([ 'controller' => 'Users', 'action' => 'register' ], $auth_data );
        $this->session([ 'Auth' => [ 'User' => [ 'id' => 1, 'username' => 'testuser' ] ] ]);
        $this->get('/users/test');
        $this->assertResponseOk('failed to access profile page');

        $this->get('/users');
        $this->assertResponseCode( 404, 'response of request with no user' );

        $this->get('/users/not_exist');
        $this->assertResponseCode( 404, 'response of request for not exist user' );
    }

}
