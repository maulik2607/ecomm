<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

      $user =   User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password'=>Hash::make('admin@123'),
            'phone' => '7896541230'
        ]);

        $roles = Role::where('name','admin')->first();

       if ($roles != null) {
    DB::table('role_users')->insert([
        'role_id' => $roles->id,
        'user_id' => $user->id,
    ]);
}
    }
}
