<?php


use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $users = [
            ['Admin', 'celinefoucart@yahoo.fr'], 
            ["Nicole", "nicole_lewis@dayrep.com"], 
            ["Hadrien", "hadrienalexander@dayrep.com"]
        ];
        foreach ($users as $user) {
            $data = [
                'username' => $user[0],
                'email' => $user[1],
                'password' => password_hash(strtolower($user[0]), PASSWORD_DEFAULT)
            ];
            $this->table('users')->insert($data)->save();
        }
        $this->table('admins')->insert(['user_id' => 1])->save();
    }
}
