<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;
use App\Model\Table\NamesTable;

/**
 * NamesFixture
 *
 */
class NamesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'username' => ['type' => 'string', 'length' => 16, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'order_key' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'name' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'type' => ['type' => 'string', 'length' => 16, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'display' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'short' => ['type' => 'string', 'length' => 16, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'preset' => ['type' => 'string', 'length' => 6, 'null' => false, 'default' => '', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'username' => ['type' => 'index', 'columns' => ['username'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'names_ibfk_1' => ['type' => 'foreign', 'columns' => ['username'], 'references' => ['users', 'username'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'username' => 'test_user',
            'order_key' => 0,
            'name' => 'Test',
            'type' => 'given',
            'display' => 'display',
            'short' => 'T',
        ],
        [
            'username' => 'smith', 
            'order_key' => 1, 
            'name' => 'John', 
            'type' => 'given', 
            'display' => NamesTable::DISPLAY['display'],
            'short' => 'J'
        ],
        [
            'username' => 'smith', 
            'order_key' => 2, 
            'name' => 'David', 
            'type' => 'middle', 
            'display' => NamesTable::DISPLAY['short'],
            'short' => 'D'
        ],
        [
            'username' => 'smith', 
            'order_key' => 3, 
            'name' => '"Aihal"', 
            'type' => 'alias', 
            'display' => NamesTable::DISPLAY['omit']
        ],
        [
            'username' => 'smith', 
            'order_key' => 4, 
            'name' => 'Smith', 
            'type' => 'family', 
            'display' => NamesTable::DISPLAY['display'],
            'short' => 'S'
        ],
        [
            'username' => 'smith', 
            'order_key' => 5, 
            'name' => '"Ged"', 
            'type' => 'true', 
            'display' => NamesTable::DISPLAY['private'],
        ],
    ];
}
