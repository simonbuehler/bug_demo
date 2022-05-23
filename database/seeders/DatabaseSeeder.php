<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make("adminadmin")
        ]);

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'superpower']);
        Permission::create(['name' => 'adminpower']);

        // create roles and assign created permissions
        Role::create(['name' => 'user']);
        Role::create(['name' => 'admin'])->givePermissionTo('adminpower');
        Role::create(['name' => 'super-admin'])->givePermissionTo(Permission::all());;

        User::all()->each(function ($item) {
            $item->assignRole('user');
        });
        User::where('name', 'admin')->first()->assignRole('super-admin');
    }
}
