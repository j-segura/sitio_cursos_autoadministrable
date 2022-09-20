<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Juan David',
            'email' => 'juanda.segura2005@gmail.com',
            'password' => bcrypt('juan2727')
        ]);

        User::factory(99)->create();
    }
}
