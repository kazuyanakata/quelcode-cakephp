<?php
use Migrations\AbstractMigration;

class CreateShippings extends AbstractMigration
{

    public $autoId = false;

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('shippings', ['id' => false, 'primary_key' => ['bidinfo_id']]);
        $table->addColumn('bidinfo_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('receive_name', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false,
        ]);
        $table->addColumn('receive_address', 'string', [
            'default' => null,
            'limit' => 1000,
            'null' => false,
        ]);
        $table->addColumn('receive_phone_number', 'string', [
            'default' => null,
            'limit' => 20,
            'null' => false,
        ]);
        $table->addColumn('is_sent', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('is_received', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('updated', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addPrimaryKey([
            'bidinfo_id',
        ]);
        $table->create();
    }
}
