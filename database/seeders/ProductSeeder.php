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
            ['name' => 'Rajčata', 'image' => '/images/tomatoes.jpg', 'order' => 10],
            ['name' => 'Brambory', 'image' => '/images/potatoes.jpg', 'order' => 20],
            ['name' => 'Jablka', 'image' => '/images/apples.jpg', 'order' => 30],
            ['name' => 'Hrušky', 'image' => '/images/pears.jpg', 'order' => 40],
            ['name' => 'Okurky', 'image' => '/images/cucumbers.jpg', 'order' => 50],
            ['name' => 'Papriky', 'image' => '/images/pepper.jpg', 'order' => 60],
            ['name' => 'Hrozny', 'image' => '/images/grape.jpg', 'order' => 70],
            ['name' => 'Česnek', 'image' => '/images/garlic.png', 'order' => 80],
            ['name' => 'Pomeranče', 'image' => '/images/orange.jpg', 'order' => 90],
            ['name' => 'Citrony', 'image' => '/images/lemon.jpg', 'order' => 100],
            ['name' => 'Mandarinky', 'image' => '/images/mandarin.jpg', 'order' => 110],
            ['name' => 'Mrkev', 'image' => '/images/carrot.png', 'order' => 120],
            ['name' => 'Petržel', 'image' => '/images/parsley.jpg', 'order' => 130],
            ['name' => 'Celer', 'image' => '/images/celery.jpg', 'order' => 140],
            ['name' => 'Jahody', 'image' => '/images/strawberry.jpg', 'order' => 150],
            ['name' => 'Borůvky', 'image' => '/images/blueberries.jpg', 'order' => 160],
            ['name' => 'Květak', 'image' => '/images/cauliflower.jpg', 'unit' => 'ks', 'order' => 170],
            ['name' => 'Cibule', 'image' => '/images/onion.jpg', 'order' => 180],
            ['name' => 'Žampiony', 'image' => '/images/mushroom.jpg', 'order' => 190],
            ['name' => 'Svíčky', 'image' => '/images/candle.jpg', 'unit' => 'ks', 'order' => 200],
            ['name' => 'Medy', 'image' => '/images/honey.jpg', 'unit' => 'ks', 'order' => 210],
            ['name' => 'Bedýnky - žampiony', 'image' => '/images/small-crate.jpg', 'order' => 220],
            ['name' => 'Bedýnky - hlíva', 'image' => '/images/large-crate.jpg', 'order' => 230],

        ];

        foreach ($products as $productData) {
            $product = new Product($productData);

            $product->save();
        }
    }
}
