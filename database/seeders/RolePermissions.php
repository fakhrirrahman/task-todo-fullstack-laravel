<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissions extends Seeder
{
    /**
     * Jalankan seeder untuk role dan permission.
     */
    public function run(): void
    {


        Role::firstOrCreate(['name' => 'pelaksana']);
        Role::firstOrCreate(['name' => 'leader']);
        Role::firstOrCreate(['name' => 'manager']);
    }
}
