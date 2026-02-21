<?php

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
        Schema::table('movements', function (Blueprint $table) {
            $table->decimal('amount', 10, 1)->change();
            $table->decimal('price', 10, 1)->change();
        });

        Schema::table('price_levels', function (Blueprint $table) {
            $table->decimal('amount', 10, 1)->change();
            $table->decimal('price', 10, 1)->change();
        });

        Schema::table('product_warehouse', function (Blueprint $table) {
            $table->decimal('amount', 10, 1)->change();
            $table->decimal('price', 10, 1)->change();
        });

        Schema::table('checks', function (Blueprint $table) {
            $table->decimal('discount', 10, 1)->change();
        });

        Schema::table('discounts', function (Blueprint $table) {
            $table->decimal('amount', 10, 1)->change();
        });

        Schema::table('check_product', function (Blueprint $table) {
            $table->decimal('amount_before', 10, 1)->change();
            $table->decimal('amount_after', 10, 1)->change();
            $table->decimal('price', 10, 1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movements', function (Blueprint $table) {
            $table->float('amount', 8, 1)->change();
            $table->float('price', 8, 1)->change();
        });

        Schema::table('price_levels', function (Blueprint $table) {
            $table->float('amount', 8, 1)->change();
            $table->float('price', 8, 1)->change();
        });

        Schema::table('product_warehouse', function (Blueprint $table) {
            $table->float('amount', 8, 1)->change();
            $table->float('price', 8, 1)->change();
        });

        Schema::table('checks', function (Blueprint $table) {
            $table->float('discount', 8, 1)->change();
        });

        Schema::table('discounts', function (Blueprint $table) {
            $table->float('amount', 8, 1)->change();
        });

        Schema::table('check_product', function (Blueprint $table) {
            $table->float('amount_before', 8, 1)->change();
            $table->float('amount_after', 8, 1)->change();
            $table->float('price', 8, 1)->change();
        });
    }
};
