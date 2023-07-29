<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductWarehouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_warehouse', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->unique(['warehouse_uuid', 'product_uuid', 'price']);

            $table->float('amount', 8, 1);
            $table->float('price', 8, 1);

            $table->foreignUuid('product_uuid')->constrained('products', 'uuid');
            $table->foreignUuid('warehouse_uuid')->constrained('warehouses', 'uuid');

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
        Schema::dropIfExists('product_warehouse');
    }
}
