<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Josef Bursík',
                'email' => 'josef.bursik@seznam.cz',
                'password' => Hash::make('samuraj'),
                'role' => User::ROLE_ADMIN,
                'warehouse_id' => 1,
            ],
            [
                'name' => 'Martin Bahula',
                'email' => 'bahula@seznam.cz',
                'password' => Hash::make('loupežník'),
                'role' => User::ROLE_ADMIN,
                'warehouse_id' => 1,
            ],
            [
                'name' => 'Jaroslav Nožička',
                'email' => 'FAKE_TILL_YOU_MAKE_IT_1@google.com',
                'password' => Hash::make('okurek'),
                'role' => User::ROLE_EMPLOYEE,
                'warehouse_id' => 2,
            ],
            [
                'name' => 'Honza',
                'email' => 'FAKE_TILL_YOU_MAKE_IT_2',
                'password' => Hash::make('okurek'),
                'role' => User::ROLE_EMPLOYEE,
                'warehouse_id' => 3,
            ],
            [
                'name' => 'Tomáš',
                'email' => 'FAKE_TILL_YOU_MAKE_IT_3',
                'password' => Hash::make('okurek'),
                'role' => User::ROLE_EMPLOYEE,
                'warehouse_id' => 4,
            ],
        ];

        foreach ($users as $userData) {
            $user = new User($userData);
            $user->save();
        }
    }
}
