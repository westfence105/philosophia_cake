<?php
use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{
    public function up()
    {

        $this->table( 'users', [ 'id' => false, 'primary_key' => ['username'] ])
            ->addColumn('username', 'string',
                [
                    'default' => null,
                    'limit' => 16,
                    'null' => false,
                ])
            ->addColumn('password', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->create();

        $this->table('profile_data', [ 'id' => false, 'primary_key' => [''] ])
            ->addColumn('username', 'string', [
                'default' => null,
                'limit' => 16,
                'null' => true,
            ])
            ->addColumn('order_key', 'integer', [
                'default' => -1,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('type', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('data', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'username',
                ]
            )
            ->create();

        $this->table('profile_data')
            ->addForeignKey(
                'username',
                'users',
                'username',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();
    }

    public function down()
    {
        $this->table('profile_data')
            ->dropForeignKey(
                'username'
            );

        $this->dropTable('profile_data');
        $this->dropTable('users');
    }
}
