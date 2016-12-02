<?php
use Migrations\AbstractMigration;

class AlterColumnLanguage extends AbstractMigration
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
        $opt = [
            'default' => '',
            'null' => true,
            'limit' => 8, 
        ];

        $table_list = [
            'users',
            'temp_users',
            'profile_data',
            'document_versions',
        ];
        foreach( $table_list as $i => $table_name ){
            $this->table( $table_name )
                 ->changeColumn('language', 'string', $opt )
                 ->update();
        }

        $this->table('names')
             ->changeColumn('preset', 'string', $opt )
             ->update();
    }
}
