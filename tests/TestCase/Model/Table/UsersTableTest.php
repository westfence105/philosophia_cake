<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Users') ? [] : ['className' => 'App\Model\Table\UsersTable'];
        $this->Users = TableRegistry::get('Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Users);

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
        $invalid_args = [ [], [ 't' => null ], [ 'username' => null ],
                [ 'username' => 'test', 'password' => null ],
                [ 'username' => null, 'password' => 'password' ],
                [ 'username' => 't', 'password' => 'password' ],    //username too short
                [ 'username' => 'test', 'password' => 'pass' ],     //password too short
                [ 'username' => 't@st', 'password' => 'password' ], //invalid username
                [ 'username' => 'test', 'password' => 'パスワード' ],  //invalid password (not ascii)
                [ 'username' => 'test', 'password' => 'пароль' ]    //invalid password (not ascii)
            ];
        foreach ( $invalid_args as $key => $value ) {
            $entity = $this->Users->newEntity( $value );
            $this->assertNotEmpty( $entity->errors(), json_encode( $value ) );
        }

        //Assert success
        $entity = $this->Users->newEntity([ 'username' => 'test', 'password' => 'password' ]);
        $this->assertEmpty( $entity->errors(), 'failed to create' );
        if( ! $this->Users->save($entity) ){
            $this->fail( 'failed to save (null)' );
        }
        $this->assertEmpty( $entity->errors(), 'failed to save (error)' );
        $this->assertEquals( $entity->username, 'test' );
        try {
            $this->Users->get('test');
        }
        catch(RecordNotFoundException $e) {
            $this->fail('failed to get saved data');
        }

        //Assert error when duplicate username
        $entity = $this->Users->newEntity([ 'username' => 'test', 'password' => 'password' ]);
        if( ! $entity->errors() ){
            $this->Users->save($entity);
        }
        $this->assertNotEmpty( $entity->errors(), 'duplicate' );
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $entity = $this->Users->newEntity([ 'username' => 'test', 'password' => 'password' ]);
        $this->assertEmpty($entity->errors(),'failed to create first entity');
        $this->Users->save($entity);
        $this->assertEmpty($entity->errors(),'failed to save first entity');
        try {
            $this->Users->get('test');
        }
        catch(RecordNotFoundException $e) {
            $this->fail('failed to get first data');
        }

        $entity = $this->Users->newEntity(['username' => 'test', 'password' => 'password'], ['validate' => false ]);
        $this->assertEmpty($entity->errors(),'failed to create socond entity');
        $this->assertTrue($entity->isNew(),'entity is not new');
        if( $this->Users->save($entity,['checkExisting'=>false]) ){
            $this->assertNotEmpty($entity->errors(),'duplicate user passed rule');
        }
    }
}
