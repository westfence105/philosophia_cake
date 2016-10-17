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
