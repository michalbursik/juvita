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
            ['name' => 'Brambory', 'image' => '/images/potatoes.jpg', 'order' => 10],
            ['name' => 'Cibule', 'image' => '/images/onion.jpg', 'order' => 20],
            ['name' => 'Česnek', 'image' => '/images/garlic.png', 'order' => 30],
            ['name' => 'Jablka', 'image' => '/images/apples.jpg', 'order' => 40],
            ['name' => 'Hrušky', 'image' => '/images/pears.jpg', 'order' => 50],
            ['name' => 'Mrkev', 'image' => '/images/carrot.png', 'order' => 60],
            ['name' => 'Petržel', 'image' => '/images/parsley.jpg', 'order' => 70],
            ['name' => 'Celer', 'image' => '/images/celery.jpg', 'order' => 80],
            ['name' => 'Rajčata', 'image' => '/images/tomatoes.jpg', 'order' => 90],
            ['name' => 'Okurky', 'image' => '/images/cucumbers.jpg', 'order' => 100],
            ['name' => 'Papriky', 'image' => '/images/pepper.jpg', 'order' => 110],
            ['name' => 'Paprika kapie', 'image' => '/images/pepper-kapie.jpg', 'order' => 120],
            ['name' => 'Citrony', 'image' => '/images/lemon.jpg', 'order' => 130],
            ['name' => 'Žampiony', 'image' => '/images/mushroom.jpg', 'order' => 140],
            ['name' => 'Hlíva', 'image' => '/images/mushroom-2.jpg', 'order' => 150],
            ['name' => 'Hrozny', 'image' => '/images/grape.jpg', 'order' => 160],
            ['name' => 'Svíčky', 'image' => '/images/candle.jpg', 'unit' => 'ks', 'order' => 170],
            ['name' => 'Medy', 'image' => '/images/honey.jpg', 'unit' => 'ks', 'order' => 180],
            ['name' => 'Pomeranče', 'image' => '/images/orange.jpg', 'order' => 190],
            ['name' => 'Mandarinky', 'image' => '/images/mandarin.jpg', 'order' => 200],

            ['name' => 'Jahody', 'image' => '/images/strawberry.jpg', 'order' => 210],
            ['name' => 'Borůvky', 'image' => '/images/blueberries.jpg', 'order' => 220],
            ['name' => 'Květak', 'image' => '/images/cauliflower.jpg', 'unit' => 'ks', 'order' => 230],
            ['name' => 'Bedýnky - žampiony', 'image' => '/images/small-crate.jpg', 'order' => 240],
            ['name' => 'Bedýnky - hlíva', 'image' => '/images/large-crate.jpg', 'order' => 250],
            ['name' => 'Čalamáda', 'image' => '/images/calamada.png', 'order' => 260],
            ['name' => 'Paprika 100g sypaná', 'image' => '/images/pepper-bag.jpg', 'order' => 270],
            ['name' => 'Banány', 'image' => '/images/banana.jpg', 'order' => 280],
            ['name' => 'Cukety', 'image' => '/images/zucchini.jpg', 'order' => 290],
            ['name' => 'Kedlubny', 'image' => '/images/kohlrabi.jpg', 'order' => 300],
            ['name' => 'Pórek', 'image' => '/images/leek.jpg', 'order' => 310],
            ['name' => 'Ředkvičky', 'image' => '/images/radishes.jpg', 'order' => 320],
            ['name' => 'Brambory sadba', 'image' => '/images/potatoes-sadba.jfif', 'order' => 330],
            ['name' => 'Zelí', 'image' => '/images/cabbage.jpg', 'order' => 340],
            ['name' => 'Bylinky', 'image' => '/images/herbs.jpg', 'order' => 350],
            ['name' => 'Sazenice jahody', 'image' => '/images/strawberry-plants.jpg', 'order' => 360],
            ['name' => 'Sáčky', 'unit' => 'ks', 'image' => '/images/bags.jpg', 'order' => 370],
            ['name' => 'Krouhané zelí', 'unit' => 'ks', 'image' => '/images/grated-cabbage.jpeg', 'order' => 380],
            ['name' => 'Cibulka sazečka', 'unit' => 'ks', 'image' => '/images/onion-planter.jpg', 'order' => 390],
            ['name' => 'Okurky nakladačky', 'unit' => 'ks', 'image' => '/images/cucumber-loader.jpg', 'order' => 400],
            ['name' => 'Salát hlávkový', 'unit' => 'ks', 'image' => '/images/lettuce.jpg', 'order' => 410],
            ['name' => 'Čínské zelí', 'unit' => 'ks', 'image' => '/images/beijing-cabbage.jpg', 'order' => 420],
            ['name' => 'Ledový salát', 'unit' => 'ks', 'image' => '/images/lettuce2.jpg', 'order' => 430],
            ['name' => 'Brokolice', 'unit' => 'ks', 'image' => '/images/broccoli.jpg', 'order' => 440],
            ['name' => 'Kapusta', 'unit' => 'ks', 'image' => '/images/cabbage2.jpg', 'order' => 450],
            ['name' => 'Nektarinky', 'unit' => 'ks', 'image' => '/images/nectarines.jpg', 'order' => 460],
            ['name' => 'Meruňky', 'unit' => 'ks', 'image' => '/images/apricots.jpg', 'order' => 470],
            ['name' => 'Broskve', 'unit' => 'ks', 'image' => '/images/peaches.jpg', 'order' => 480],
            ['name' => 'Švestky', 'unit' => 'ks', 'image' => '/images/plums.jpg', 'order' => 490],
            ['name' => 'Bloomy', 'unit' => 'ks', 'image' => '/images/blumy.jpeg', 'order' => 500],

            ['name' => 'Jarní cibulka', 'unit' => 'ks', 'image' => '/images/spring-onion.png', 'order' => 510],
            ['name' => 'Přerostky', 'unit' => 'ks', 'image' => '/images/overgrowths.webp', 'order' => 520],
            ['name' => 'Kopr', 'unit' => 'ks', 'image' => '/images/dill.jpeg', 'order' => 530],
            ['name' => 'Křen', 'unit' => 'ks', 'image' => '/images/horseradish.jpeg', 'order' => 540],
            ['name' => 'Kukuřice - 3ks', 'unit' => 'ks', 'image' => '/images/corn.avif', 'order' => 550],
            ['name' => 'Kozí rohy', 'unit' => 'ks', 'image' => '/images/goat_horns.png', 'order' => 560],
            ['name' => 'Červená řepa', 'unit' => 'ks', 'image' => '/images/blooms.avif', 'order' => 570],


        ];

        foreach ($products as $productData) {
            Product::createWithAttributes($productData);
        }
    }
}
