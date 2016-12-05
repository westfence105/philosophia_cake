<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

use Cake\Auth\DefaultPasswordHasher;

/**
 * UsersFixture
 *
 */
class UsersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'username' => ['type' => 'string', 'length' => 16, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'password' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => '', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'language' => ['type' => 'string', 'length' => 6, 'null' => true, 'default' => '', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'email' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => '', 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['username'], 'length' => []],
            'email' => ['type' => 'unique', 'columns' => ['email'], 'length' => []],
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
    public function init(){
        $this->records = [
            [
                'username' => 'user',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'language' => '',
                'email' => 'test_user@example.com'
            ],
            [
                'username' => 'smith',
                'password' => (new DefaultPasswordHasher)->hash('password'),
                'language' => 'en_US',
                'email' => 'smith@example.com'
            ],
        ];

        parent::init();
    }
}
