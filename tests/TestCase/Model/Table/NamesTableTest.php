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
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
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
            $this->assertEmpty( $entity->errors(), json_encode($args) );
        }
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testBeforeMarshal(){
        $entity = $this->Names->newEntity([
                'username'  => 'smith',
                'name'      => 'Baron',
                'type'      => 'title',
                'display'   => 'display',
                'preset'    => 'en',
            ]);
        $this->assertEmpty( $entity->errors() );
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
        $this->assertEmpty( $entity->errors(), json_encode($entity->errors) );
        $presets = $this->Names->getPresets('smith');
        $this->assertEquals( count($presets), 3, json_encode($presets) );
    }

    public function testGetName(){
        //display_lebel => normal
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

        //display_lebel => full
        //expected: 'John David "Aihal" Smith'
        $names = $this->Names->getName('smith', 'en', [ 'display_lebel' => 'full' ]);
        $expected = [
                ['name' => 'John',      'type' => 'given'],
                ['name' => 'David',     'type' => 'middle'],
                ['name' => '"Aihal"',   'type' => 'alias'],
                ['name' => 'Smith',     'type' => 'family'],
            ];
        $this->assertEquals( $expected, $names );

        //display_lebel => private
        //expected: 'John David "Aihal" Smith "Ged"'
        $names = $this->Names->getName('smith', 'en', ['display_lebel' => 'private']);
        $expected = [
                ['name' => 'John',      'type' => 'given'],
                ['name' => 'David',     'type' => 'middle'],
                ['name' => '"Aihal"',   'type' => 'alias'],
                ['name' => 'Smith',     'type' => 'family'],
                ['name' => '"Ged"',     'type' => 'true'],
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
                    ['name' => 'Иванович', 'type' => 'petronym', 'display' => 'display', 'short' => 'И'],
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
                    ['name' => 'Иванович',  'type' => 'petronym', 'display' => 'display', 'short' => 'И'],
                    ['name' => 'Иванов',    'type' => 'family',   'display' => 'display', 'short' => 'И'],
                ],
            ],
        ];
        $other_user_data = $this->Names->getNameData('test_user');

        foreach ( $test_data as $i => $data ) {
            $orig = $this->Names->getNameData( $username, ['display' => 'string'] );
            $ret = $this->Names->setNameData( $username, $data, ['display' => 'string'] );
            $this->assertNotFalse( $ret, 'assert returning value of setNameData not false' );
            $this->assertEquals( $data, $ret, 'assert returning value is same as argument' );
            $expected = array_replace( $orig, $data );
            $result = $this->Names->getNameData( $username, ['display' => 'string'] );
            $this->assertEquals( $expected, $result, 'assert data already set' );
        }

        $data = $this->Names->getNameData('test_user');
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

        $tu_exp = $this->Names->getNameData('test_user');
        $this->Names->removePreset('test_user','');
        unset($tu_exp['']);
        $this->assertEquals( $tu_exp, $this->Names->getNameData('test_user'), 'assert remove works' );
        $data = $this->Names->getNameData('smith');
        $this->assertEquals( $data_old, $data, 'assert never remove others' );
    }
}
