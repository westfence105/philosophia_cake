<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NamesTable;
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
        'app.names'
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
        $sample = ['username' => 'test', 'order_key' => 1, 'name' => 'John', 'type' => 'given', 'display' => 1 ];
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
                array_merge( $sample, ['clipped' => '' ]),
                array_merge( $sample, ['clipped' => 'J' ]),
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

    public function array_subset( array $exp, array $values ){
        foreach ( $values as $key => $value) {
            foreach ( $exp as $e_key => $e_value) {
                if( isset($value[$e_key]) && $value[$e_key] == $e_value ){
                    continue;
                }
                else {
                    return false;
                }
            }
        }
        return true;
    }

    public function testGetName(){
        //display_lebel => normal
        //expected: 'John D Smith'
        $names = $this->Names->getName('smith'); //
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
        $names = $this->Names->getName('smith', NamesTable::DISPLAY_LEBEL['full']);
        $expected = [
                ['name' => 'John',      'type' => 'given'],
                ['name' => 'David',     'type' => 'middle'],
                ['name' => '"Aihal"',   'type' => 'alias'],
                ['name' => 'Smith',     'type' => 'family'],
            ];
        $this->assertEquals( $expected, $names );

        //display_lebel => private
        //expected: 'John David "Aihal" Smith "Ged"'
        $names = $this->Names->getName('smith', NamesTable::DISPLAY_LEBEL['private']);
        $expected = [
                ['name' => 'John',      'type' => 'given'],
                ['name' => 'David',     'type' => 'middle'],
                ['name' => '"Aihal"',   'type' => 'alias'],
                ['name' => 'Smith',     'type' => 'family'],
                ['name' => '"Ged"',     'type' => 'true'],
            ];
        $this->assertEquals( $expected, $names );
    }
}
