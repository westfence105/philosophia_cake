<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AcceptLanguagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AcceptLanguagesTable Test Case
 */
class AcceptLanguagesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AcceptLanguagesTable
     */
    public $AcceptLanguages;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.accept_languages'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('AcceptLanguages') ? [] : ['className' => 'App\Model\Table\AcceptLanguagesTable'];
        $this->AcceptLanguages = TableRegistry::get('AcceptLanguages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AcceptLanguages);

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
