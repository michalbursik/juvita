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
            $table->index('type');
            $table->index('product_id');
            $table->index('issue_warehouse_id');
            $table->index('receipt_warehouse_id');
            $table->index('created_at');
        });

        Schema::table('price_levels', function (Blueprint $table) {
            $table->index(['warehouse_id', 'product_id', 'amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movements', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['product_id']);
            $table->dropIndex(['issue_warehouse_id']);
            $table->dropIndex(['receipt_warehouse_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('price_levels', function (Blueprint $table) {
            $table->dropIndex(['warehouse_id', 'product_id', 'amount']);
        });
    }
};
