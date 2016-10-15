<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TempUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TempUsersTable Test Case
 */
class TempUsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\TempUsersTable
     */
    public $TempUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.temp_users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TempUsers') ? [] : ['className' => 'App\Model\Table\TempUsersTable'];
        $this->TempUsers = TableRegistry::get('TempUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TempUsers);

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
