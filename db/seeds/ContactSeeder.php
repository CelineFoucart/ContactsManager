<?php

use Phinx\Seed\AbstractSeed;

class ContactSeeder extends AbstractSeed
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
        $faker = Faker\Factory::create('fr_FR');        
        for ($i=0; $i < 40; $i++) {
            $adress = explode("\n", $faker->address());
            $data = [
                "firstname" => $faker->firstName(), 
                "lastname" => $faker->lastName(), 
                "email" => $faker->email(), 
                "number_phone" => $faker->mobileNumber(), 
                "address" => $adress[0], 
                "city" => $adress[1], 
                "country" => "France", 
                "user_id" => rand(1, 3)
            ];
            $this->table('contacts')->insert($data)->save();
        }
    }
}
