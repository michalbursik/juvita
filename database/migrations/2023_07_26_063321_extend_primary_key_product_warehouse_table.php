<?php

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_warehouse', function (Blueprint $table) {
            // TODO remove after production is cleared

//            $table->dropForeignIdFor(Product::class);
//            $table->dropForeignIdFor(Warehouse::class);
//            $table->dropPrimary();
//
//            $table->primary(['product_id', 'warehouse_id', 'price']);
//
//            $table->foreign('product_id')
//                ->references('id')
//                ->on('products');
//
//            $table->foreign('warehouse_id')
//                ->references('id')
//                ->on('warehouses');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
