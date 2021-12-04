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
                'password' => Hash::make('samuraj')
            ],
            [
                'name' => 'Martin Bahula',
                'email' => 'bahula@seznam.cz',
                'password' => Hash::make('loupežník')
            ],
            [
                'name' => 'Jaroslav Nožička',
                'email' => 'FAKE_TILL_YOU_MAKE_IT_1',
                'password' => Hash::make('okurek')
            ],
            [
                'name' => 'Honza',
                'email' => 'FAKE_TILL_YOU_MAKE_IT_2',
                'password' => Hash::make('okurek')
            ],
            [
                'name' => 'Tomáš',
                'email' => 'FAKE_TILL_YOU_MAKE_IT_3',
                'password' => Hash::make('okurek')
            ],
        ];

        foreach ($users as $userData) {
            $user = new User($userData);
            $user->save();
        }
    }
}
