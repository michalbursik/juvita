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
            ['name' => 'Rajčata', 'image' => '/images/tomatoes.jpg'],
            ['name' => 'Brambory', 'image' => '/images/potatoes.jpg'],
            ['name' => 'Jablka', 'image' => '/images/apples.jpg'],
            ['name' => 'Hrušky', 'image' => '/images/pears.jpg'],
            ['name' => 'Okurky', 'image' => '/images/cucumbers.jpg'],
            ['name' => 'Papriky', 'image' => '/images/pepper.jpg'],
            ['name' => 'Hrozny', 'image' => '/images/grape.jpg'],
            ['name' => 'Česnek', 'image' => '/images/garlic.png'],
            ['name' => 'Pomeranče', 'image' => '/images/orange.jpg'],
            ['name' => 'Citrony', 'image' => '/images/lemon.jpg'],
            ['name' => 'Mandarinky', 'image' => '/images/mandarin.jpg'],
            ['name' => 'Mrkev', 'image' => '/images/carrot.png'],
            ['name' => 'Petržel', 'image' => '/images/parsley.jpg'],
            ['name' => 'Celer', 'image' => '/images/celery.jpg'],
            ['name' => 'Jahody', 'image' => '/images/strawberry.jpg'],
            ['name' => 'Borůvky', 'image' => '/images/blueberries.jpg'],
            ['name' => 'Květak', 'image' => '/images/cauliflower.jpg', 'unit' => 'ks'],
            ['name' => 'Cibule', 'image' => '/images/onion.jpg'],
            ['name' => 'Žampiony', 'image' => '/images/mushroom.jpg'],
            ['name' => 'Svíčky', 'image' => '/images/candle.jpg', 'unit' => 'ks'],
            ['name' => 'Medy', 'image' => '/images/honey.jpg', 'unit' => 'ks'],
            ['name' => 'Bedínky - žampiony', 'image' => '/images/small-crate.jpg'],
            ['name' => 'Bedínky - hlíva', 'image' => '/images/large-crate.jpg'],

        ];

        foreach ($products as $productData) {
            $product = new Product($productData);

            $product->save();
        }
    }
}
