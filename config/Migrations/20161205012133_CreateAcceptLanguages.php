<?php
use Migrations\AbstractMigration;

class CreateAcceptLanguages extends AbstractMigration
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
        $table = $this->table('accept_languages');
        $table->addColumn('username', 'string', [
            'limit' => 16,
            'null' => false,
        ]);
        $table->addColumn('order_key', 'integer', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('language', 'string', [
            'default' => null,
            'limit' => 8,
            'null' => false,
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
