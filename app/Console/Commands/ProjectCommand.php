<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Console\Command;

class ProjectCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:project';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $warehouse = Warehouse::createWithAttributes([
            'name' => 'WarehouseName',
            'type' => Warehouse::TYPE_MAIN
        ]);

        $warehouseTo = Warehouse::createWithAttributes([
            'name' => 'WarehouseName',
            'type' => Warehouse::TYPE_TEMPORARY
        ]);

        $product = Product::createWithAttributes([
            'name' => 'ProductName',
            'order' => Product::getNextOrderNumber()
        ]);

        $warehouse->receiveProduct($product->uuid, 10, 20);
        $warehouse->receiveProduct($product->uuid, 10, 20);

        $warehouse->moveProduct($product->uuid,10, 25);

        // TODO Is this necessary??
         // $warehouse->transferProductTo($warehouseTo->uuid, $product->uuid, 10, 5);
    }
}
