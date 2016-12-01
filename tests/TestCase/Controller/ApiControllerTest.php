<?php
namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestCase;

/**
 * Api TestCase base class
 */
abstract class ApiControllerTest extends IntegrationTestCase
{

    public function setUp(){
        parent::setUp();
        
        $this->enableSecurityToken();
        $this->enableCsrfToken();
        $this->configRequest([
                'environment' => [ 'HTTPS' => 'on' ]
            ]);
    }

    protected function ajax( string $func, string $url, $data = [] ){
        $token = 'test-csrf-token';
        $this->cookie('csrfToken', $token );
        $this->configRequest([
            'headers' => [
                'X-Requested-With' => 'XMLHttpRequest',
                'Accept' => 'application/json',
                'Content-Type' => 'application/json; charset=utf-8',
                'X-Csrf-Token' => $token,
            ],
            'input' => json_encode($data),
        ]);

        if( $func == 'get' ){
            return $this->get($url);
        }
        else{
            return $this->$func($url);
        }
    }

}
