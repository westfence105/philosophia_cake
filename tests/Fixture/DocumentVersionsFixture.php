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
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'language' => ['type' => 'string', 'length' => 6, 'null' => false, 'default' => '', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'data_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_indexes' => [
            'data_id' => ['type' => 'index', 'columns' => ['data_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id', 'language'], 'length' => []],
            'document_versions_ibfk_1' => ['type' => 'foreign', 'columns' => ['id'], 'references' => ['documents', 'id'], 'update' => 'cascade', 'delete' => 'cascade', 'length' => []],
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
            'language' => '5473083d-c642-45c2-bb4b-7f9f801a1c45',
            'data_id' => 1,
            'created' => '2016-11-10 02:46:26',
            'modified' => '2016-11-10 02:46:26'
        ],
    ];
}
