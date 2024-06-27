<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        $role1 = Role::create(['name' => 'simple']);
        $role2 = Role::create(['name' => 'event_creator']);
        $role3 = Role::create(['name' => 'admin']);
    }
}
