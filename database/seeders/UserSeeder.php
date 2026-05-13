<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Принимающий заявки',
            'email' => 'order@cafe.ru',
            'password' => bcrypt('password'),
            'role' => 'order_taker',
        ]);

        User::create([
            'name' => 'Повар',
            'email' => 'cook@cafe.ru',
            'password' => bcrypt('password'),
            'role' => 'cook',
        ]);

        User::create([
            'name' => 'Руководитель',
            'email' => 'admin@cafe.ru',
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);
    }
}
