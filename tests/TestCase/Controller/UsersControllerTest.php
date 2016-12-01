<?php
namespace App\Test\TestCase\Controller;

use App\Controller\UsersController;

use Cake\Log\Log;
use Cake\Http\Client;
use Cake\Mailer\Email;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * App\Controller\UsersController Test Case
 */
class UsersControllerTest extends ApiControllerTest
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

    public function setUp(){
        parent::setUp();
        $this->session([ 'Auth.User.username' => 'smith' ]);
    }

    public function testView(){
        $this->ajax('get','/api/1.0/users/user.json');
        $this->assertResponseOk();
        $ret = json_decode( $this->_response->body(), true );

        $this->ajax('get','/api/1.0/users/not_exist.json');
        $this->assertResponseCode(404);
    }
}
