<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        \App\Models\User::create([
            'name' => 'UTN Admin',
            'email' => 'utn.admin@admin.com',
            'password' => Hash::make('utn_admin08'),
            'role_id' => 1,
        ]);
    }
}
