<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['pelaksana', 'leader', 'manager'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        $user1 = User::create([
            'name' => 'manager',
            'username' => 'manager',
            'password' => bcrypt('password'),
        ]);
        $user2 = User::create([
            'name' => 'Leader User',
            'username' => 'leader',
            'password' => bcrypt('password'),
        ]);
        $user3 = User::create([
            'name' => 'Pelaksana User',
            'username' => 'pelaksana',
            'password' => bcrypt('password'),
        ]);

        $user1->assignRole('manager');
        $user3->assignRole('pelaksana');
        $user2->assignRole('leader');
    }
}
