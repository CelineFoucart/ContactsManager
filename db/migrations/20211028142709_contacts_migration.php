<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ContactsMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $this->table('users')
        ->addColumn('username', 'string', ['limit' => 100])
        ->addColumn("email", "string", ['limit' => 255])
        ->addColumn('password', 'string', ['limit' => 100])
        ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
        ->addIndex(['username', 'email'], ['unique' => true])
        ->create();

        $this->table('contacts')
        ->addColumn("firstname", "string", ['limit' => 100])
        ->addColumn("lastname","string", ['limit' => 100])
        ->addColumn("email","string", ['limit' => 255])
        ->addColumn("number_phone","string", ['limit' => 16])
        ->addColumn("address", "string", ['limit' => 255])
        ->addColumn("city", "string", ['limit' => 200])
        ->addColumn("country", "string", ['limit' => 200])
        ->addColumn("user_id", "integer")
        ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
        ->create();
    }
}
