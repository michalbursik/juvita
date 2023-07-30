<?php

use App\Models\WarehouseProduct;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouse_product_prices', function (Blueprint $table) {
            $warehouseProduct = new WarehouseProduct();

            $table->id();
            $table->uuid()->unique();

            $table->foreignUuid($warehouseProduct->getForeignKey())->constrained($warehouseProduct->getTable(), $warehouseProduct->getKeyName());

            $table->float('amount', 12, 4);
            $table->float('price', 12, 4);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_product_prices');
    }
};
