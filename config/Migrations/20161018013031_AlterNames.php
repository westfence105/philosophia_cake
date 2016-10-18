<?php
use Migrations\AbstractMigration;

class AlterNames extends AbstractMigration
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
        $table->renameColumn('clipped','short');
        $table->update();
    }
}
