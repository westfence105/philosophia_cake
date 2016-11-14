<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DocumentVersionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DocumentVersionsTable Test Case
 */
class DocumentVersionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DocumentVersionsTable
     */
    public $DocumentVersions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.document_versions',
        'app.document_data',
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
        $config = TableRegistry::exists('DocumentVersions') ? [] : ['className' => 'App\Model\Table\DocumentVersionsTable'];
        $this->DocumentVersions = TableRegistry::get('DocumentVersions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DocumentVersions);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $entity = $this->DocumentVersions->newEntity([
                        'document_id' => 1,
                        'language' => 'en',
                        'data_id' => 1,
                    ]);
        $this->assertEmpty( $entity->errors(), json_encode($entity->errors()) );

        $required = ['document_id' => 1, 'data_id' => 1 ];
        foreach( $required as $key => $value ){
            $args = $required;
            unset($args[$key]);
            $entity = $this->DocumentVersions->newEntity($args);
            $this->assertNotEmpty( $entity->errors(), "entity passed validation without $key" );
        }

        $entity = $this->DocumentVersions->newEntity([
                        'document_id' => 1,
                        'language' => 'duplicate',
                        'data_id' => 1,
                    ]);
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        //assert success
        $entity = $this->DocumentVersions->newEntity([
                        'document_id' => 1,
                        'language' => 'en',
                        'data_id' => 1,
                    ]);
        $this->DocumentVersions->save($entity);
        $this->assertEmpty( $entity->errors(), json_encode($entity->errors()) );

        $entity = $this->DocumentVersions->newEntity([
                        'document_id' => 1,
                        'data_id' => 1,
                    ]);
        $this->DocumentVersions->save($entity);
        $this->assertEmpty( $entity->errors(), json_encode($entity->errors()) );

        //assert fail
        $entity = $this->DocumentVersions->newEntity([
                        'document_id' => 99,
                        'language' => 'en',
                        'data_id' => 1,
                    ]);
        $this->DocumentVersions->save($entity);
        $this->assertNotEmpty( $entity->errors(), "entity passed rule with id that doesn't exist" );

        $entity = $this->DocumentVersions->newEntity([
                        'document_id' => 1,
                        'language' => 'en',
                        'data_id' => 99,
                    ]);
        $this->DocumentVersions->save($entity);
        $this->assertNotEmpty( $entity->errors(), "entity passed rule with data_id that doesn't exist" );
    }
}
