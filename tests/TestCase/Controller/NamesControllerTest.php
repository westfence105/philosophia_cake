<?php
namespace App\Test\TestCase\Controller;

use App\Controller\DocumentsController;
use Cake\TestSuite\IntegrationTestCase;

class NamesControllerTest extends IntegrationTestCase
{

    public $fixtures = [
        'app.users',
        'app.names',
    ];

	const URL = '/api/1.0/user/names';

    public function setUp(){
        parent::setUp();
        
        $this->enableSecurityToken();
        $this->configRequest([
                'environment' => [ 'HTTPS' => 'on' ]
            ]);
        $this->session([ 'Auth.User.username' => 'smith' ]);
        $this->enableCsrfToken();
    }

    public function testNotAjaxDenied(){
        $this->get( self::URL.'.json' );
        $this->assertResponseCode(400);
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

    protected function validateNames( array $names ){
        foreach ( $names as $i => $name ) {
            $this->assertInternalType('integer', $i, 'content of preset is not array' );
            $this->assertArrayHasKey('name', $name );
            $this->assertArrayHasKey('type', $name );
            $this->assertArrayHasKey('display', $name );
            $this->assertArrayHasKey('short',   $name );
            $this->assertInternalType('string', $name['display'] );
        }
    }

    public function testIndex(){
        $this->setAjaxHeader();        
        $this->get( self::URL.'.json' );
        $this->assertResponseOk();
        $ret = json_decode( $this->_response->body(), true );
        foreach( $ret as $preset => $names ){
            $this->assertNotEmpty( $preset, 'data has empty "preset" name' );
            $this->assertInternalType('string', $preset, 'preset name is not string');
            $this->validateNames( $names );
        }
    }

    public function testView(){
        $this->setAjaxHeader();        
        $this->get( self::URL.'/en.json' );
        $this->assertResponseOk();
        $ret = json_decode( $this->_response->body(), true );
        $this->validateNames( $ret );
    }
}