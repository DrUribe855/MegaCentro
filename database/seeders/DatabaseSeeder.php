<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use \App\Models\User;
use \App\Models\Clinic;
use \App\Models\Tower;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    
    public function run()
    {
        $this->fake_tower();
        $users = User::factory(30)->create();
        $this->fake_clinic();
        $this->call(RoleSeeder::class);

        

        $adminRole = Role::where('name','Administrador')->first();
        $assistantRole = Role::where('name','Auxiliar contable')->first();
        $collectorRole = Role::where('name','Recolector')->first();
        $managerRole = Role::where('name', 'Encargado')->first();

        $users[0]->assignRole($adminRole);
        $users[1]->assignRole($assistantRole);
        $users[2]->assignRole($collectorRole);
        $users[3]->assignRole($managerRole);

    }


    public function fake_tower(){
        $j = 1;
        for ($i=0; $i < 3; $i++) { 
            Tower::insert([
                'id' => $j,
            ]);
            $j++;
        }
    }

    public function fake_clinic(){
        for ($i=0; $i < 300; $i++) { 
            $clinic_number = rand(100,500);
            $user_id = rand(1,30);
            $tower_id = rand(1,3);
            while (Clinic::where('clinic_number', $clinic_number)->exists()) {
                $clinic_number = rand(100, 500);
            }
            Clinic::insert([
                'clinic_number' => $clinic_number,
                'user_id' => $user_id,
                'tower_id' => $tower_id,
            ]);
        }
    }
}
