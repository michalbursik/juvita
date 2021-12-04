<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            ['name' => 'Rajčata'],
            ['name' => 'Brambory'],
            ['name' => 'Jablka'],
            ['name' => 'Hrušky'],
            ['name' => 'Okurky'],
            ['name' => 'Papriky'],
            ['name' => 'Hrozny'],
            ['name' => 'Česnek'],
            ['name' => 'Pomeranče'],
            ['name' => 'Citrony'],
            ['name' => 'Mandarinky'],
            ['name' => 'Mrkev'],
            ['name' => 'Petržel'],
            ['name' => 'Celer'],
            ['name' => 'Jahody'],
            ['name' => 'Borůvky'],
            ['name' => 'Květak'],
            ['name' => 'Cibule'],
            ['name' => 'Žampiony'],
            ['name' => 'Hlíva'],
            ['name' => 'Svíčky', 'unit' => 'ks'],
            ['name' => 'Medy', 'unit' => 'ks'],
        ];

        foreach ($products as $productData) {
            $product = new Product($productData);

            $product->save();
        }
    }
}
