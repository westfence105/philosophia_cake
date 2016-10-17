<?php
use Migrations\AbstractMigration;

class CreateNames extends AbstractMigration
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
        $table = $this->table('names');
        $table->addColumn('username', 'string', [
            'limit' => 16,
            'null' => false,
        ]);
        $table->addColumn('order_key', 'integer', [
            'null' => false,
        ]);
        $table->addColumn('name', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('type', 'string', [
            'limit' => 16,
            'null' => false,
        ]);
        $table->addColumn('display', 'integer', [
            'null' => false,
        ]);
        $table->addColumn('clipped', 'string', [
            'limit' => 16,
            'null' => true,
        ]);
        $table->create();

        $table->addForeignKey(
            'username',
            'users', 'username',
            [ 'update' => 'CASCADE', 'delete' => 'CASCADE' ]
        );
        $table->update();
    }
}
