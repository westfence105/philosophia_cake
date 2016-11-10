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
        $this->markTestIncomplete('Not implemented yet.');
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
