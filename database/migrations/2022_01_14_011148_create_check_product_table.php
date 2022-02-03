<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_product', function (Blueprint $table) {
            $table->id();

            $table->float('amount_before');
            $table->float('amount_after');
            $table->decimal('price')->nullable();

            $table->foreignId('check_id')->constrained('checks');
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('price_level_id')->constrained('price_levels');

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
        Schema::dropIfExists('check_product');
    }
}
