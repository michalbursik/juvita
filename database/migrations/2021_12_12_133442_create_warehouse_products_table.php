<?php

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_products', function (Blueprint $table) {
            $product = new Product();
            $warehouse = new Warehouse();

            $table->id();
            $table->uuid()->unique();
            $table->unique([$warehouse->getForeignKey(), $product->getForeignKey()]);

            $table->foreignUuid($product->getForeignKey())->constrained($product->getTable(), $product->getKeyName());
            $table->foreignUuid($warehouse->getForeignKey())->constrained($warehouse->getTable(), $warehouse->getKeyName());

            $table->float('total_amount', 12, 4);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_products');
    }
};
