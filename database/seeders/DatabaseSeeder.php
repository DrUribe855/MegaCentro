<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use \App\Models\User;
use \App\Models\Clinic;
use App\Models\Clinic_user;
use \App\Models\Tower;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    
    public function run()
    {
        $this->fake_tower();
        $users = User::factory(30)->create();
        // $this->fake_clinic();
        $this->call(RoleSeeder::class);
        $this->call(MorePermissionsSeeder::class);

        

        $adminRole = Role::where('name','Administrador')->first();
        $assistantRole = Role::where('name','Auxiliar contable')->first();
        $collectorRole = Role::where('name','Recolector')->first();
        $managerRole = Role::where('name', 'Responsable')->first();
        $ownerRole = Role::where('name', 'Dueño')->first();

        $users[0]->assignRole($adminRole);
        $users[1]->assignRole($assistantRole);
        $users[2]->assignRole($collectorRole);
        $users[3]->assignRole($managerRole);
        $users[4]->assignRole($ownerRole);

    }


    public function fake_tower(){
        $j = 1;
        for ($i=0; $i < 2; $i++) { 
            Tower::insert([
                'id' => $j,
            ]);
            $j++;
        }
    }

    // public function fake_clinic(){
    //     for ($i=0; $i < 300; $i++) { 
    //         $clinic_number = rand(100,500);
    //         $floor = rand(1,10);
    //         $tower_id = rand(1,2);
    //         while (Clinic::where('clinic_number', $clinic_number)->exists()) {
    //             $clinic_number = rand(100, 500);
    //         }
    //         Clinic::insert([
    //             'clinic_number' => $clinic_number,
    //             'floor' => $floor,
    //             'tower_id' => $tower_id,
    //             'status' => 'DESOCUPADO',
    //             'collection_status' => 'SIN RECOLECTAR',
    //         ]);
    //     }
    // }
}
