<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
     
    public function run()
    {
        $administratorRole = Role::create(['name' => 'Administrador']);
        $assistantRole = Role::create(['name' => 'Auxiliar Contable']);
        $collectorRole = Role::create(['name' => 'Recolector']);

        Permission::create(['name' => 'admin.employees.generalShow'])->assignRole($administratorRole);
        Permission::create(['name' => 'admin.employees.create']);
        Permission::create(['name' => 'admin.employees.edit']);
    }
}
