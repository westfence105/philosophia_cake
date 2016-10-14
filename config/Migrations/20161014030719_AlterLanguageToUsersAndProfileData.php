<?php
use Migrations\AbstractMigration;

class AlterLanguageToUsersAndProfileData extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function up()
    {
        $this->table('users')
                ->changeColumn('language', 'string', [
                    'default' => '',
                    'limit' => 6,
                    'null' => true,
                ])
                ->update();

        $this->table('profile_data')
                ->changeColumn('language', 'string', [
                    'default' => '',
                    'limit' => 6,
                    'null' => true,
                ])
                ->update();
    }
}
?>