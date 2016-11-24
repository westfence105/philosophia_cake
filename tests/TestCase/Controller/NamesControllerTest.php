<?php
namespace App\Test\TestCase\Controller;

use App\Controller\DocumentsController;
use Cake\TestSuite\IntegrationTestCase;

use Cake\Log\Log;

use App\Model\Validation\NameValidator;

class NamesControllerTest extends IntegrationTestCase
{

    public $fixtures = [
        'app.users',
        'app.names',
    ];

	const URL = '/api/1.0/users/names';

    public function setUp(){
        parent::setUp();
        
        $this->enableSecurityToken();
        $this->enableCsrfToken();
        $this->configRequest([
                'environment' => [ 'HTTPS' => 'on' ]
            ]);
        $this->session([ 'Auth.User.username' => 'smith' ]);
    }

    public function testNotAjaxDenied(){
        $this->get( self::URL.'.json' );
        $this->assertResponseError();
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
        $this->ajax( 'get', self::URL.'.json' );
        $this->assertResponseOk();
        $ret = json_decode( $this->_response->body(), true );
        foreach( $ret as $preset => $names ){
            $this->assertNotEmpty( $preset, 'data has empty "preset" name' );
            $this->assertInternalType('string', $preset, 'preset name is not string');
            $this->validateNames( $names );
        }
    }

    public function testView(){
        $this->ajax( 'get', self::URL.'/en.json' );
        $this->assertResponseOk();
        $ret = json_decode( $this->_response->body(), true );
        $this->validateNames( $ret );

        $this->ajax( 'get', self::URL.'/eo.json' );
        $this->assertResponseCode(404);
    }

    public function testEdit(){
        $valid_data = [
            [
                'names' => [
                    ['name' => 'Jacob',  'type' => 'given', 'display' => 'omit', 'short' => 'J.'],
                    ['name' => 'Isaac',  'type' => 'patronym', 'display' => 'omit', 'short' => 'I.'],
                    ['name' => '"Jack"', 'type' => 'alias', 'display' => 'short', 'short' => 'Jack'],
                    ['name' => 'Williams', 'type' => 'family', 'display' => 'display' ],
                ],
            ],
        ];

        $base = ['name' => 'John', 'type' => 'given', 'display' => 'display', 'short' => 'J.' ];
        $invalid_data = [
            array_merge( $base, ['name' => '']),        //empty name
            array_merge( $base, ['type' => 'giben']),   //invalid type
            array_merge( $base, ['type' => 'petronym']),//invalid type
            array_merge( $base, ['display' => 'srt']),  //invalid display
            array_merge( $base, ['display' => 'short', 'short' => '']), //display "short" without short
        ];
        foreach( ['name','type','display'] as $i => $key ){
            $d = $base;
            unset($d[$key]);
            $invalid_data[] = $d;
        }

        $preset = 'en';
        foreach ( $valid_data as $i => $data ) {
            $this->ajax('put', self::URL."/$preset.json", $data );
            $this->assertResponseOk();

            $preset = array_key_exists('preset', $data ) ? $data['preset'] : ( $data['preset'] = $preset );
            $exp = $data;
            if( array_key_exists('names', $exp ) ){
                foreach( $exp['names'] as $i => &$name ){
                    if( !array_key_exists('name', $name ) ){
                        $name['name'] = null;
                    }
                    if( !array_key_exists('short', $name ) ){
                        $name['short'] = null;
                    }
                }
                unset($name);
            }
            
            $ret = json_decode( $this->_response->body(), true );
            $this->assertEquals( $exp, $ret, 'returning value of PUT(edit) is not same expected value');

            $this->ajax('get', self::URL."/$preset.json", $data );
            $this->assertResponseOk();
            $ret_view = json_decode( $this->_response->body(), true );
            $this->assertEquals( $ret['names'], $ret_view );
        }

        $validator = new NameValidator();
        foreach ( $invalid_data as $j => $data ) {
            for( $i = 0; $i < 2; ++$i ){
                $names = [];
                for( $c = 0; $c < $i; ++$c ){
                    $names[] = $base;
                }
                $names[$i] = $data;
                $this->ajax( 'put', self::URL.'/en.json', [
                        'names' => $names,
                    ]);
                $this->assertResponseCode(400);
                $ret = json_decode( $this->_response->body(), true );
                $exp = [
                    'errors' => [
                        "$i" => $validator->errors($data),
                    ],
                ];
                $this->assertEquals( $exp, $ret );
            }
        }
    }

    public function testDelete(){
        $this->ajax( 'delete', self::URL.'/en.json' );
        $this->assertResponseOk();

        //assert deleted
        $this->ajax( 'get', self::URL.'/en.json' );
        $this->assertResponseCode(404);

        $this->ajax( 'delete', self::URL.'/en.json' );
        $this->assertResponseCode(404);
    }
}