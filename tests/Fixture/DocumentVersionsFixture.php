<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * DocumentVersionsFixture
 *
 */
class DocumentVersionsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'document_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'language' => ['type' => 'string', 'length' => 6, 'null' => true, 'default' => '', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'data_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'data_id' => ['type' => 'index', 'columns' => ['data_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'document_id' => ['type' => 'unique', 'columns' => ['document_id', 'language'], 'length' => []],
            'document_versions_ibfk_1' => ['type' => 'foreign', 'columns' => ['document_id'], 'references' => ['documents', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
            'document_versions_ibfk_2' => ['type' => 'foreign', 'columns' => ['data_id'], 'references' => ['document_data', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
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
            'id' => 1,
            'document_id' => 1,
            'language' => 'Lore',
            'data_id' => 1,
            'created' => '2016-11-11 03:27:53',
            'modified' => '2016-11-11 03:27:53'
        ],
    ];
}
