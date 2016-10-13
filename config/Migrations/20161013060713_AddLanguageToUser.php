<?php
use Migrations\AbstractMigration;

class AddLanguageToUser extends AbstractMigration
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
        $table = $this->table('users');
        $table->addColumn('language', 'string', [
            'default' => '',
            'limit' => 3,
            'null' => true,
        ]);
        $table->update();
    }
}
