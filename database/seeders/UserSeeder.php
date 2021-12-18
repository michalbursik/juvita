<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Warehouse;
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
                'warehouse_id' => 1,
            ],
            [
                'name' => 'Martin Bahula',
                'email' => 'bahula@seznam.cz',
                'password' => Hash::make('loupežník'),
                'warehouse_id' => 1,
            ],
            [
                'name' => 'Jaroslav Nožička',
                'email' => 'FAKE_TILL_YOU_MAKE_IT_1',
                'password' => Hash::make('okurek'),
                'warehouse_id' => 2,
            ],
            [
                'name' => 'Honza',
                'email' => 'FAKE_TILL_YOU_MAKE_IT_2',
                'password' => Hash::make('okurek'),
                'warehouse_id' => 3,
            ],
            [
                'name' => 'Tomáš',
                'email' => 'FAKE_TILL_YOU_MAKE_IT_3',
                'password' => Hash::make('okurek'),
                'warehouse_id' => 4,
            ],
        ];

        foreach ($users as $userData) {
            $user = new User($userData);
            $user->save();
        }
    }
}
