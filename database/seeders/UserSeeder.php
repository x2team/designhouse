<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        // generate 36 dummy posts users
        $users = [];
        $faker = Factory::create('vi_VN');

        $users[]       =  [
            'name'     => 'Admin',
            'username' => 'admin',
            'email'    => 'admin@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'remember_token' => Str::random(10),
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ];

        DB::table('users')->insert($users);
          
    }
}
