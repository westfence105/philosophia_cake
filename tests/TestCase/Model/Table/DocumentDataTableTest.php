<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DocumentDataTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DocumentDataTable Test Case
 */
class DocumentDataTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DocumentDataTable
     */
    public $DocumentData;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.document_data',
        'app.documents'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('DocumentData') ? [] : ['className' => 'App\Model\Table\DocumentDataTable'];
        $this->DocumentData = TableRegistry::get('DocumentData', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DocumentData);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $entity = $this->DocumentData->newEntity(['document_id' => 1 ]);
        $this->assertEmpty( $entity->errors(), json_encode( $entity->errors() ) );

        $entity = $this->DocumentData->newEntity([
                        'document_id' => 1,
                        'language' => 'en',
                        'title' => 'Test',
                        'text' => 'This is a test document.',
                        'is_draft' => true,
                    ]);
        $this->assertEmpty( $entity->errors(), json_encode( $entity->errors() ) );

        $entity = $this->DocumentData->newEntity([]);
        $this->assertNotEmpty( $entity->errors(), "entity passed validation without document_id" );
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $entity = $this->DocumentData->newEntity(['document_id' => 1 ]);
        $this->DocumentData->save($entity);
        $this->assertEmpty( $entity->errors(), json_encode( $entity->errors() ) );

        $entity = $this->DocumentData->newEntity(['document_id' => 99 ]);
        $this->DocumentData->save($entity);
        $this->assertNotEmpty( $entity->errors(), "entity passed rule with document_id that doesn't exist" );
    }
}
