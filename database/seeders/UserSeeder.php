<?php

namespace Database\Seeders;

use App\Enums\UserRole;
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
                'role' => UserRole::ADMIN->value,
                'warehouse_id' => 1,
            ],
            [
                'name' => 'Martin Bahula',
                'email' => 'bahula@seznam.cz',
                'password' => Hash::make('seba381'),
                'role' => UserRole::ADMIN->value,
                'warehouse_id' => 1,
            ],
            [
                'name' => 'Jaroslav Nožička',
                'email' => 'jaroslav@juvita.cz',
                'password' => Hash::make('okurek'),
                'role' => UserRole::EMPLOYEE->value,
                'warehouse_id' => 2,
            ],
            [
                'name' => 'Honza Hlaváček',
                'email' => 'honza@juvita.cz',
                'password' => Hash::make('precedenc'),
                'role' => UserRole::EMPLOYEE->value,
                'warehouse_id' => 3,
            ],
            [
                'name' => 'Tomáš Strapina',
                'email' => 'tomas@juvita.cz',
                'password' => Hash::make('kultivator'),
                'role' => UserRole::EMPLOYEE->value,
                'warehouse_id' => 4,
            ],
        ];

        foreach ($users as $userData) {
            $user = new User($userData);
            $user->save();
        }
    }
}
