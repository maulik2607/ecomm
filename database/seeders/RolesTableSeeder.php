<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleList = ['admin','staff','customer','superadmin'];

        foreach($roleList as $val){

          if (!DB::table('roles')->where('name', $val)->exists()) {
              DB::table('roles')->insert(['name' => $val]);
            }
        }
    }
}
