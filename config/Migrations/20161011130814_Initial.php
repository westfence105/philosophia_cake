<?php
use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{
    public function up()
    {

        $this->table( 'users', [ 'id' => false, 'primary_key' => ['username'] ])
            ->addColumn('username', 'string',
                [
                    'limit' => 16,
                    'null' => false,
                ])
            ->addColumn('password', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => true,
            ])
            ->create();

        $this->table('profile_data')
            ->addColumn('username', 'string', [
                'limit' => 16,
                'null' => false,
            ])
            ->addColumn('order_key', 'integer', [
                'default' => -1,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('type', 'text', [
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('data', 'text', [
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
