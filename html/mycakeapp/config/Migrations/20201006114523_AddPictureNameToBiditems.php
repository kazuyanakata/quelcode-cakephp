<?php
use Migrations\AbstractMigration;

class AddPictureNameToBiditems extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('biditems');
        $table->addColumn('picture_name', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false,
        ]);
        $table->update();
    }
}
