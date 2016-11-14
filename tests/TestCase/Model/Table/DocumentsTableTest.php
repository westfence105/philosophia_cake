<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DocumentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DocumentsTable Test Case
 */
class DocumentsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DocumentsTable
     */
    public $Documents;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users',
        'app.documents',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Documents') ? [] : ['className' => 'App\Model\Table\DocumentsTable'];
        $this->Documents = TableRegistry::get('Documents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Documents);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $entity = $this->Documents->newEntity(['username' => 'user']);
        $this->assertEmpty( $entity->errors(), json_encode( $entity->errors() ) );

        $entity = $this->Documents->newEntity([
                    'username' => 'user',
                    'category' => 'politics',
                    'parent' => 1,
                    'standpoint' => false,
                    'counter' => 0,
                    'launched' => null,
                ]);
        $this->assertEmpty( $entity->errors(), json_encode( $entity->errors() ) );

        $entity = $this->Documents->newEntity([]);
        $this->assertNotEmpty( $entity->errors(), "entity passed validation without username" );
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $entity = $this->Documents->newEntity(['username' => 'user']);
        $this->Documents->save($entity);
        $this->assertEmpty( $entity->errors(), $entity->errors() );

        $entity = $this->Documents->newEntity(['username' => 'not_exist']);
        $this->Documents->save($entity);
        $this->assertNotEmpty( $entity->errors(), "entity passed rule with username that doesn't exist" );
    }
}
