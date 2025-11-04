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
        // Daftar permission sesuai flow task (harus sinkron dengan authorize/can di controller/views)
        $permissions = [
            // Pelaksana
            'submit task',       // Membuat task baru
            'revise task',       // Melakukan revisi task (setelah dikembalikan)
            'update task',       // Mengubah detail task (edit)
            'update progress',   // Update progress task
            'complete task',     // Menyelesaikan task

            // Leader
            'review task',       // Melihat dan review task dari Pelaksana (akses halaman review)
            'override progress', // Koreksi/override progress oleh leader

            // Manager
            'monitor task',      // Melihat seluruh task
        ];

        // Buat permission jika belum ada
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Buat role jika belum ada
        $pelaksana = Role::firstOrCreate(['name' => 'pelaksana']);
        $leader    = Role::firstOrCreate(['name' => 'leader']);
        $manager   = Role::firstOrCreate(['name' => 'manager']);

        // Pelaksana permissions
        $pelaksana->syncPermissions([
            'submit task',
            'revise task',
            'update task',
            'update progress',
            'complete task',
        ]);

        // Leader permissions
        $leader->syncPermissions([
            'review task',
            'override progress',
            // allow leader to complete task as per flow (Pelaksana/Leader dapat menyelesaikan)
            'complete task',
        ]);

        // Manager permissions
        $manager->syncPermissions([
            'monitor task',
        ]);
    }
}
