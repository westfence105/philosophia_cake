<?php
use Migrations\AbstractMigration;

class CreateTempUsers extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('temp_users', [ 'id' => false, 'primary_key' => 'token' ]);
        $table->addColumn('username', 'string', [
            'default' => null,
            'limit' => 16,
            'null' => false,
        ]);
        $table->addColumn('password', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('language', 'string', [
            'default' => '',
            'limit' => 6,
            'null' => true,
        ]);
        $table->addColumn('email', 'string', [
            'default' => null,
            'null' => false,
            'limit' => 255,
        ]);
        $table->addColumn('token', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addIndex(['username', 'email'],['unique' => true ]);
        $table->create();
    }
}
