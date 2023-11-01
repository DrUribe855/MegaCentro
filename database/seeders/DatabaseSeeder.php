<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use \App\Models\User;
use \App\Models\Clinic;
use App\Models\Clinic_user;
use \App\Models\Tower;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->fake_user();
        $this->fake_tower();
        $this->fake_clinic();
    }

    public function fake_user(){
        $faker = Faker::create();
        $document = 108800;
        $phone_number = 3217076300;
        for ($i=0; $i < 50; $i++) { 
            $role = '';
            if ($i%2 == 0) {
                $role = 'Recolector';
            }else{
                $role = 'Dueno';
            }
            
            if ($i%3 == 0) {
                $role = 'Responsable';
            }else if (($i%4 == 0)) {
                $role = 'Administrador';
            }
            User::insert([
                'document' => $document,
                'name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'phone' => $phone_number++,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt($document),
                'role' => $role,
                'status' => 'Activo'
                 
            ]);
            $document++;
        }
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
            $tower_id = rand(1,3);
            $status = '';
            while (Clinic::where('clinic_number', $clinic_number)->exists()) {
                $clinic_number = rand(100, 500);
            }
            Clinic::insert([
                'clinic_number' => $clinic_number,
                'tower_id' => $tower_id,
                'status' => 'DESOCUPADO',
            ]);
        }
    }
}
