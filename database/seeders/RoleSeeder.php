<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Root', 'slug' => 'ROOT']);
        Role::create(['name' => 'Admin', 'slug' => 'ADMIN']);
        Role::create(['name' => 'Organizer', 'slug' => 'ORGANIZER']);
    }
}
