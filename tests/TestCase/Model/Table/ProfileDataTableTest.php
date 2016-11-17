<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ProfileDataTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ProfileDataTable Test Case
 */
class ProfileDataTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ProfileDataTable
     */
    public $ProfileData;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users',
        'app.profile_data',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ProfileData') ? [] : ['className' => 'App\Model\Table\ProfileDataTable'];
        $this->ProfileData = TableRegistry::get('ProfileData', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ProfileData);

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
        $invalid_args = [ [], [ 't' => null ], [ 'username' => null ] ];
        foreach ( $invalid_args as $key => $value ) {
            $entity = $this->ProfileData->newEntity( $value );
            $this->assertNotEmpty( $entity->errors(), json_encode( $value ) );
        }

        $valid_args = [
                [
                    'username' => 'user'
                ]
            ];
        foreach ( $valid_args as $key => $value ) {
            $entity = $this->ProfileData->newEntity( $value );
            if( $entity->errors() ){
                $this->ProfileData->save($entity);
            }
            $this->assertEmpty( $entity->errors(), json_encode( $value ) );
        }
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $entity = $this->ProfileData->newEntity(['username' => 'user']);
        $this->ProfileData->save($entity);
        $this->assertEmpty($entity->errors(), json_encode($entity->errors()) );
        unset($entity);

        $invalid_args = [
            ['username' => 'not_exists'],
        ];
        foreach( $invalid_args as $i => $args ){
            $entity = $this->ProfileData->newEntity(['username' => 'not_exists']);
            $this->ProfileData->save($entity);
            $this->assertNotEmpty($entity->errors());
            unset($entity);
        }
    }
}
