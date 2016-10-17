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
}
