<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
          'name' => 'David Weber',
          'email' => 'david.weber.schenker@gmail.com',
          'password' => bcrypt('12345'),
        ]);

        DB::table('users')->insert([
          'name' => 'Micheal Faller',
          'email' => 'micheal.faller@gmail.com',
          'password' => bcrypt('12345'),
        ]);
    }
}
