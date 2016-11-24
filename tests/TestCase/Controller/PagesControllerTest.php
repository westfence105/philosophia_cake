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

/**
 * PagesControllerTest class
 */
class PagesControllerTest extends IntegrationTestCase
{
    public $fixtures = [
        'app.users',
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
