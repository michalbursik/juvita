<?php

namespace Database\Seeders;

use App\Enums\WarehouseTypeEnum;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use App\Repositories\WarehouseProductRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WarehouseSeeder extends Seeder
{
    private WarehouseProductRepository $warehouseProductRepository;

    public function __construct(WarehouseProductRepository $warehouseProductRepository)
    {
        $this->warehouseProductRepository = $warehouseProductRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $warehouses = [
            [
                'data' => ['name' => 'Uherský Brod - Králov', 'type' => WarehouseTypeEnum::MAIN],
                'user' => [
                    'name' => 'Josef Bursík',
                    'email' => 'josef.bursik@seznam.cz',
                    'password' => Hash::make('samuraj'),
                    'role' => User::ROLE_ADMIN,
                ]
            ],
            [
                'data' => ['name' => 'Vozidlo Jaroslav', 'type' => WarehouseTypeEnum::TEMPORARY],
                'user' => [
                    'name' => 'Martin Bahula',
                    'email' => 'bahula@seznam.cz',
                    'password' => Hash::make('seba381'),
                    'role' => User::ROLE_ADMIN,
                ]
            ],
            [
                'data' => ['name' => 'Vozidlo Honza', 'type' => WarehouseTypeEnum::TEMPORARY],
                'user' => [
                    'name' => 'Jaroslav Nožička',
                    'email' => 'jaroslav@juvita.cz',
                    'password' => Hash::make('okurek'),
                    'role' => User::ROLE_EMPLOYEE,
                ]
            ],
            [
                'data' => ['name' => 'Vozidlo Tomáš', 'type' => WarehouseTypeEnum::TEMPORARY],
                'user' => [
                    'name' => 'Honza Hlaváček',
                    'email' => 'honza@juvita.cz',
                    'password' => Hash::make('precedenc'),
                    'role' => User::ROLE_EMPLOYEE,
                ]
            ],
            [
                'data' => ['name' => 'Prodej', 'type' => WarehouseTypeEnum::INTERNAL],
                'user' => [
                    'name' => 'Tomáš Strapina',
                    'email' => 'tomas@juvita.cz',
                    'password' => Hash::make('kultivator'),
                    'role' => User::ROLE_EMPLOYEE,
                ]
            ],
            ['data' => ['name' => 'Kompost/Odpad ', 'type' => WarehouseTypeEnum::TRASH]],
        ];

        foreach ($warehouses as $warehouseData) {
            $warehouse = Warehouse::createWithAttributes($warehouseData['data']);

            $products = Product::all();

            /** @var Product $product */
            foreach ($products as $product) {
                $this->warehouseProductRepository->create(
                    $warehouse->uuid, $product->uuid
                );
            }

            if (!empty($warehouseData['user'])) {
                $data = $warehouseData['user'];
                $data = [...$data, 'warehouse_uuid' => $warehouse->uuid];
                $user = User::createWithAttributes($data);
            }
        }
    }
}
