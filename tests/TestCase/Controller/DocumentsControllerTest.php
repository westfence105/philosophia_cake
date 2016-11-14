<?php
namespace App\Test\TestCase\Controller;

use App\Controller\DocumentsController;
use Cake\TestSuite\IntegrationTestCase;

class DocumentsControllerTest extends IntegrationTestCase
{

    public $fixtures = [
        'app.users',
        'app.documents',
    ];

	const URL = '/api/1.0/documents';

    public function setUp(){
        parent::setUp();
        
        $this->enableSecurityToken();
        $this->configRequest([
                'environment' => [ 'HTTPS' => 'on' ]
            ]);
        $this->session([ 'Auth.User.username' => 'user' ]);
        $this->enableCsrfToken();
    }

    public function testNotAjaxDenied(){
    	$this->get( self::URL );
    	$this->assertResponseError();
    }

    protected function setAjaxHeader(){
    	$this->configRequest([
    		'headers' => [
    			'X-Requested-With' => 'XMLHttpRequest',
    			'Accept' => 'application/json',
    			'Content-Type' => 'application/json; charset=utf-8',
    		],
    	]);
    }

    public function testView(){
    	$this->setAjaxHeader();
    	$this->get( self::URL.'/1.json' );
    	$this->assertResponseOk();
    	$expected = ['id' => 1 ];
    	$this->assertEquals( $expected, json_decode($this->_response->body(), true ) );
    }
}