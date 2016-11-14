<?php
use Migrations\AbstractMigration;

class CreateDocuments extends AbstractMigration
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
        $this->table('documents', ['id' => false, 'primary_key' => ['id'] ])
             ->addColumn('id', 'integer', ['null' => false ])
             ->addColumn('username', 'string', ['limit' => 16, 'null' => false ])
             ->addColumn('category','string', ['null' => true, 'default' => ''])
             ->addColumn('parent', 'integer', ['null' => true ])
             ->addColumn('standpoint','boolean',['null' => true ])
             ->addColumn('counter', 'integer',  ['default' => 0 ] )
             ->addColumn('launched','datetime', ['null' => true ])
             ->addIndex(['username','parent'])
             ->addForeignKey('username','users','username',['update' => 'CASCADE', 'delete' => 'CASCADE'])
             ->addForeignKey('parent','documents','id',['update' => 'CASCADE', 'delete' => 'CASCADE' ])
             ->create();

        $this->table('document_data')
             ->addColumn('document_id','integer')
             ->addColumn('language', 'string', ['limit' => 6, 'null' => true, 'default' => ''])
             ->addColumn('title','text',['null' => true, 'default' => ''])
             ->addColumn('text','text', ['null' => true, 'default' => ''])
             ->addColumn('is_draft','boolean',['null' => true, 'default' => false ])
             ->addColumn('created', 'datetime', ['null' => true ])
             ->addColumn('modified','datetime', ['null' => true ])
             ->addIndex(['document_id','language'])
             ->addForeignKey('document_id','documents','id',['update' => 'CASCADE', 'delete' => 'CASCADE'])
             ->create();

        $this->table('document_versions')
             ->addColumn('document_id', 'integer', ['null' => false ])
             ->addColumn('language', 'string',  ['limit' => 6, 'null' => true, 'default' => ''])
             ->addColumn('data_id', 'integer',  ['null' => false ])
             ->addColumn('created', 'datetime', ['null' => true ])
             ->addColumn('modified','datetime', ['null' => true ])
             ->addIndex(['document_id','language'],['unique' => true ])
             ->addForeignKey('document_id','documents','id',['update' => 'CASCADE', 'delete' => 'CASCADE'])
             ->addForeignKey('data_id','document_data','id',['update' => 'CASCADE', 'delete' => 'CASCADE'])
             ->create();
    }
}
