<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleIdUser = DB::table('roles')
            ->where('name', '=', 'user')
            ->value('id');

        $roleIdAdmin = DB::table('roles')
            ->where('name', '=', 'admin')
            ->value('id');

        DB::table('users')->insert([
            'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
            'role_id' => $roleIdUser,
            'name' => Str::random(10),
            'email' => Str::random(10) . '@gmail.com',
            'password' => Hash::make('password'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('users')->insert([
            'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),
            'role_id' => $roleIdAdmin,
            'name' => Str::random(10),
            'email' => Str::random(10) . '@gmail.com',
            'password' => Hash::make('password'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
