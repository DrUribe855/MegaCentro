<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    
    public function run()
    {

        $this->call(RoleSeeder::class);

        $users = User::factory(30)->create();

        $adminRole = Role::where('name','Administrador')->first();
        $userRole = Role::where('name','Auxiliar contable')->first();

        $users[0]->assignRole($adminRole);
        $users[1]->assignRole($userRole);

        
    }
}
