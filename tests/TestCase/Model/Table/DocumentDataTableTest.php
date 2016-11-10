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
