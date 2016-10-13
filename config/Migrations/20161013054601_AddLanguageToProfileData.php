<?php
use Migrations\AbstractMigration;

class AddLanguageToProfileData extends AbstractMigration
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
        $table = $this->table('profile_data');
        $table->addColumn('language', 'string', [
            'default' => '',
            'limit' => 3,
            'null' => true
        ]);
        $table->update();
    }
}
