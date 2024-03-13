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
        $managerRole = Role::create(['name' => 'Responsable']);
        $ownerRole = Role::create(['name' => 'Dueño']);

        // ------------------------ Rol administrador -------------------- \\

        //Permisos de modulo empleados
        Permission::create(['name' => 'admin.employees.generalShow'])->assignRole($administratorRole);
        Permission::create(['name' => 'admin.employees.create'])->assignRole($administratorRole);
        Permission::create(['name' => 'admin.employees.edit'])->assignRole($administratorRole);

        //Permisos de modulo de encargados
        Permission::create(['name' => 'admin.managers.generalShow'])->assignRole($assistantRole, $administratorRole);
        Permission::create(['name' => 'admin.managers.create'])->assignRole($administratorRole);
        Permission::create(['name' => 'admin.managers.edit'])->assignRole($administratorRole);

        // ------------------------ Rol asistente -------------------------- \\

        Permission::create(['name' => 'assistant.statistics.generalShow'])->assignRole($assistantRole, $administratorRole);
        Permission::create(['name' => 'assistant.statistics.create'])->assignRole($administratorRole);
        Permission::create(['name' => 'assistant.statistics.edit'])->assignRole($administratorRole);    

        // ------------------------ Rol recolector ---------------------------- \\

        Permission::create(['name' => 'collector.collections.generalShow'])->assignRole($collectorRole);
        Permission::create(['name' => 'collector.collections.create'])->assignRole($collectorRole);
        Permission::create(['name' => 'collector.collections.edit'])->assignRole($collectorRole);
        Permission::create(['name' => 'collector.storedWaste.view'])->assignRole($collectorRole);
        

        // ------------------------ Rol encargado y dueño -------------------------------- \\

        Permission::create(['name' => 'manager.clinics.generalShow'])->assignRole($managerRole, $ownerRole);
    }
}
