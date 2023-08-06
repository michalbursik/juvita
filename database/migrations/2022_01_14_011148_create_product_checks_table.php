<?php

use App\Models\Check;
use App\Models\Price;
use App\Models\WarehouseProduct;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_checks', function (Blueprint $table) {
            $check = new Check();
            $warehouseProduct = new WarehouseProduct();
            $price = new Price();

            $table->id();
            $table->uuid()->unique();

            $table->float('amount_before', 12, 4);
            $table->float('amount_after', 12, 4);
            $table->float('price', 12, 4)->nullable();
            $table->float('total_price', 12, 4)->nullable();

            $table->foreignUuid($check->getForeignKey())->constrained(
                $check->getTable(),
                $check->getKeyName()
            );
            $table->foreignUuid($warehouseProduct->getForeignKey())->constrained(
                $warehouseProduct->getTable(),
                $warehouseProduct->getKeyName()
            );
            $table->foreignUuid($price->getForeignKey())->constrained(
                $price->getTable(),
                $price->getKeyName()
            );

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_checks');
    }
};
