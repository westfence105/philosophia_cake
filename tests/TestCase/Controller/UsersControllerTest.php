<?php
namespace App\Test\TestCase\Controller;

use App\Controller\UsersController;
use Cake\TestSuite\IntegrationTestCase;

use Cake\I18n\I18n;

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

    public function testUsers()
    {
        //test register
        $auth_data = [ 'username' => 'test', 'password' => 'password', 'language' => 'ja_JP' ];
        $this->enableCsrfToken();
        $this->post([ 'controller' => 'Users', 'action' => 'register' ], $auth_data );
        $this->assertResponseOk('failed to add user');

        //test login
        $this->post([ 'controller' => 'Users', 'action' => 'login' ], $auth_data );
        $this->assertRedirect('/');

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
