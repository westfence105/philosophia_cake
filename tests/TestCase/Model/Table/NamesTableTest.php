<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NamesTable;
use App\Model\Entity\Name;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\NamesTable Test Case
 */
class NamesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\NamesTable
     */
    public $Users;
    public $Names;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users',
        'app.names',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Names') ? [] : ['className' => 'App\Model\Table\NamesTable'];
        $this->Names = TableRegistry::get('Names', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Names);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $sample = ['username' => 'smith', 'name' => 'John', 'type' => 'given', 'display' => 1, 'preset' => 'en' ];
        foreach ( $sample as $key => $value) {
            $args = $sample;
            $args[$key] = null;
            for ( $i=0; $i < 2 ; $i++ ) {
                $entity = $this->Names->newEntity($args);
                $this->assertNotEmpty( $entity->errors(), json_encode($args) );
                unset($args[$key]);
            }
        }

        $invalid_args = [
                [], ['t' => null ],
                array_merge( $sample, ['order_key' => '' ] ),   //order_key isn't int
                array_merge( $sample, ['order_key' => 1.2 ] ),  //order_key isn't int
                array_merge( $sample, ['order_key' => true ] ), //order_key isn't int
                array_merge( $sample, ['order_key' => [] ] ),   //order_key isn't int

                array_merge( $sample, ['display' => '' ] ),     //display isn't int
                array_merge( $sample, ['display' => 1.2 ] ),    //display isn't int
                array_merge( $sample, ['display' => true ] ),   //display isn't int
                array_merge( $sample, ['display' => [] ] ),     //display isn't int
            ];

        foreach ($invalid_args as $key => $args ) {
            $entity = $this->Names->newEntity( $args );
            $this->assertNotEmpty( $entity->errors(), json_encode($args) );
        }

        $valid_args = [
                $sample,
                array_merge( $sample, ['order_key' => 1 ]),
                array_merge( $sample, ['clipped' => '' ]),
                array_merge( $sample, ['clipped' => 'J' ]),
                array_merge( $sample, ['preset' => 'en' ]),
            ];

        foreach ($valid_args as $key => $args ) {
            $entity = $this->Names->newEntity( $args );
            $this->assertEmpty( $entity->errors(), json_encode($args)."\n".json_encode($entity->errors()) );
        }
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $sample = ['username' => 'smith', 'name' => 'John', 'type' => 'given', 'display' => 1, 'preset' => 'en' ];
        
        $entity = $this->Names->newEntity($sample);
        $this->Names->save($entity);
        $this->assertEmpty($entity->errors(), json_encode($sample)."\n".json_encode($entity->errors()) );
        unset($entity);

        $invalid_args = [
            array_merge( $sample, ['username' => 'not_exists'] ),
        ];
        foreach( $invalid_args as $i => $args ){
            $entity = $this->Names->newEntity($args);
            $this->Names->save($entity);
            $this->assertNotEmpty($entity->errors(), json_encode($args)."\n".json_encode($entity->errors()) );
            unset($entity);
        }
    }

    public function testBeforeMarshal(){
        $entity = $this->Names->newEntity([
                'username'  => 'smith',
                'name'      => 'Baron',
                'type'      => 'title',
                'display'   => 'display',
                'preset'    => 'en',
            ]);
        $this->assertEmpty( $entity->errors(), json_encode($entity->errors()) );
    }

    public function testGetPresets(){
        $presets = $this->Names->getPresets('smith');
        $this->assertEquals( count($presets), 2, json_encode($presets) );

        $entity = $this->Names->newEntity([
                'username'  => 'smith',
                'name'      => 'Иван',
                'type'      => 'given',
                'display'   => 'display',
                'order_key' => 1,
                'preset'    => 'ru',
            ]);
        if( empty( $entity->errors() ) ){
            $this->Names->save($entity);
        }
        $this->assertEmpty( $entity->errors(), json_encode($entity->errors()) );
        $presets = $this->Names->getPresets('smith');
        $this->assertEquals( count($presets), 3, json_encode($presets) );

        $user = $this->Names->Users->get('smith');
        for( $i = 0; $i < 10; ++$i ){
            $user->language = 'en';
            $ret = $this->Names->Users->save($user);
            $this->assertNotFalse( $ret );
            $this->assertEmpty( $user->errors() );
            $presets = $this->Names->getPresets('smith');
            $this->assertEquals( 'en', $presets[0] );

            $user->language = 'ja';
            $ret = $this->Names->Users->save($user);
            $this->assertNotFalse( $ret );
            $this->assertEmpty( $user->errors() );
            $presets = $this->Names->getPresets('smith');
            $this->assertEquals( 'ja', $presets[0] );
        }
    }

    public function testGetName(){
        //display_level => normal
        //expected: 'John D Smith'
        $names = $this->Names->getName('smith','en'); //
        foreach ( $names as $key => $name ) {
            $this->assertNotEmpty( $name['name'] );
            $this->assertNotEmpty( $name['type'] );
        }
        $expected = [
                ['name' => 'John',  'type' => 'given'],
                ['name' => 'D',     'type' => 'middle'],
                ['name' => 'Smith', 'type' => 'family'],
            ];
        $this->assertEquals( $expected, $names );

        //display_level => full
        //expected: 'John David "Aihal" Smith'
        $names = $this->Names->getName('smith', 'en', [ 'display_level' => 'full' ]);
        $expected = [
                ['name' => 'John',      'type' => 'given'],
                ['name' => 'David',     'type' => 'middle'],
                ['name' => '"Aihal"',   'type' => 'alias'],
                ['name' => 'Smith',     'type' => 'family'],
            ];
        $this->assertEquals( $expected, $names );

        //display_level => private
        //expected: 'John David "Aihal" Smith "Ged"'
        $names = $this->Names->getName('smith', 'en', ['display_level' => 'private']);
        $expected = [
                ['name' => 'John',      'type' => 'given'],
                ['name' => 'David',     'type' => 'middle'],
                ['name' => '"Aihal"',   'type' => 'alias'],
                ['name' => 'Smith',     'type' => 'family'],
                ['name' => '"Ged"',     'type' => 'other'],
            ];
        $this->assertEquals( $expected, $names );
    }

    public function testGetNameData(){
        $data = $this->Names->getNameData('smith');
        $this->assertInternalType('array', $data );
        foreach( $data as $preset => $names ){
            foreach ( $names as $i => $name ) {
                $this->assertArrayHasKey('name',   $name );
                $this->assertArrayHasKey('type',   $name );
                $this->assertArrayHasKey('display',$name );
                $this->assertInternalType('string', $name['name'] );
                $this->assertInternalType('string', $name['type'] );
                $this->assertInternalType('int',    $name['display'] );
            }
        }

        $data = $this->Names->getNameData('smith', ['display' => 'string']);
        foreach( $data as $preset => $names ){
            foreach ( $names as $i => $name ) {
                $this->assertInternalType('string', $name['display']);
            }
        }

        $presets = $this->Names->getPresets('smith');
        $this->assertEquals( count( $presets ), count( $data ), 
                                'asserting count( $data ) == count( $presets )' );
        $this->assertEquals( $presets, array_keys( $data ), 'assert order of name_data' );

        $this->assertEquals( 'en', array_keys( $data )[0] );
        $user = $this->Names->Users->get('smith');
        $user->language = 'ja';
        $ret = $this->Names->Users->save($user);
        $this->assertNotFalse( $ret );
        $this->assertEmpty( $user->errors() );
        $data = $this->Names->getNameData('smith');
        $this->assertEquals( 'ja', array_keys( $data )[0] );
    }

    public function testSetNameData(){
        $username = 'smith';
        $test_data = [
            [
                'en' => [
                    ['name' => 'William', 'type' => 'given',  'display' => 'short', 'short' => 'W.'],
                    ['name' => 'Joseph',  'type' => 'given', 'display' => 'short', 'short' => 'J.'],
                    ['name' => 'Smith',   'type' => 'given', 'display' => 'display', 'short' =>'Smith'],
                ],
            ],
            [ //sort
                'en' => [
                    ['name' => 'Joseph',  'type' => 'given', 'display' => 'short', 'short' => 'J.'],
                    ['name' => 'William', 'type' => 'given',  'display' => 'short', 'short' => 'W.'],
                    ['name' => 'Smith',   'type' => 'given', 'display' => 'display', 'short' =>'Smith'],
                ],
            ],
            [ //add
                'en' => [
                    ['name' => 'Joseph',  'type' => 'given', 'display' => 'display', 'short' => 'J.'],
                    ['name' => 'William', 'type' => 'given',  'display' => 'display', 'short' => 'W.'],
                    ['name' => 'Philip',  'type' => 'given', 'display' => 'display', 'short' => 'F.' ],
                    ['name' => 'Smith',   'type' => 'family', 'display' => 'display', 'short' =>'Smith'],
                ],
            ],
            [ //other preset
                'ru' => [
                    ['name' => 'Иван',     'type' => 'given',    'display' => 'display', 'short' => 'И'],
                    ['name' => 'Иванович', 'type' => 'patronym', 'display' => 'display', 'short' => 'И'],
                    ['name' => 'Иванов',   'type' => 'family',   'display' => 'display', 'short' => 'И'],
                ],
            ],
            [ //change multiple preset
                'en' => [
                    ['name' => 'Alexander', 'type' => 'given',  'display' => 'display', 'short' => 'A.'],
                    ['name' => 'John',      'type' => 'middle', 'display' => 'display', 'short' => 'J.'],
                    ['name' => 'Smith',     'type' => 'family', 'display' => 'display', 'short' =>'Smith'],
                ],
                'ru' => [
                    ['name' => 'Александр', 'type' => 'given',    'display' => 'display', 'short' => 'A'],
                    ['name' => 'Иванович',  'type' => 'patronym', 'display' => 'display', 'short' => 'И'],
                    ['name' => 'Иванов',    'type' => 'family',   'display' => 'display', 'short' => 'И'],
                ],
            ],
        ];
        $other_user_data = $this->Names->getNameData('user');

        foreach ( $test_data as $i => $data ) {
            $orig = $this->Names->getNameData( $username, ['display' => 'string'] );
            $errors = [];
            $ret = $this->Names->setNameData( $username, $data, ['display' => 'string'], $errors );
            $this->assertNotFalse( $ret, 'returning value of setNameData is false'."\n".json_encode($errors,JSON_UNESCAPED_UNICODE) );
            $this->assertEquals( $data, $ret, 'returning value isn\'t same as argument'."\n".json_encode($data) );
            $expected = array_replace( $orig, $data );
            $result = $this->Names->getNameData( $username, ['display' => 'string'] );
            $this->assertEquals( $expected, $result, 'assert data already set' );
        }

        $data = $this->Names->getNameData('user');
        $this->assertEquals( $other_user_data, $data, 'assert method never effect to other users' );
    }

    public function testRenamePreset(){
        $data_old = $this->Names->getNameData('smith');
        $ret = $this->Names->renamePreset('smith','en','en_US',['display' => 'string']);
        $this->assertNotFalse($ret);
        $data_new = $this->Names->getNameData('smith');
        $this->assertArrayNotHasKey( 'en', $data_new );
        $this->assertArrayHasKey('en_US', $data_new );
        $this->assertEquals( $data_old['en'], $data_new['en_US'] );
    }

    public function testRemovePreset(){
        $data_old = $this->Names->getNameData('smith');
        $this->Names->removePreset('',''); //nothing
        $data = $this->Names->getNameData('smith');
        $this->assertEquals( $data_old, $data, 'assert empty never remove others' );

        $tu_exp = $this->Names->getNameData('user');
        $this->Names->removePreset('user','');
        unset($tu_exp['']);
        $this->assertEquals( $tu_exp, $this->Names->getNameData('user'), 'assert remove works' );
        $data = $this->Names->getNameData('smith');
        $this->assertEquals( $data_old, $data, 'assert never remove others' );
    }
}
