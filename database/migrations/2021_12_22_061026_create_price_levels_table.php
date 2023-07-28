<?php

use App\Models\ProductWarehouse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_levels', function (Blueprint $table) {
            $table->id();
            $table->float('amount', 8, 1);
            $table->float('price', 8, 1);

            // TODO not used yet!
            $table->date('validFrom');
            $table->date('validTo');

            $table->string('status')->default(ProductWarehouse::STATUS_ACTIVE);

            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('warehouse_id')->constrained('warehouses');

            $table->softDeletes();
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
        Schema::dropIfExists('price_levels');
    }
}
