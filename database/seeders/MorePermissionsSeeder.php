<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MorePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $collectorRole = Role::findByName('Recolector');
        $administratorRole = Role::findByName('Administrador');
        $assistantRole = Role::findByName("Auxiliar contable");
        $permissionModuleCollections = Permission::findByName("collector.collections.generalShow");


        // Creacion de permisos 
        
        Permission::create(['name' => 'assistant.statistics.rhcontinuation'])->assignRole($administratorRole, $assistantRole, $collectorRole);
        $administratorRole->givePermissionTo($permissionModuleCollections);

        


    }
}
