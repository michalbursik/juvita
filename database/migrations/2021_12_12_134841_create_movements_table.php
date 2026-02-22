<?php

use App\Models\Movement;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('type')->default(Movement::TYPE_ISSUE);
            $table->float('amount', 8, 1);
            $table->float('price', 8, 1)->nullable();

            $table->foreignUuid('product_id')->constrained('products');
            $table->foreignUuid('user_id')->constrained('users');
            $table->foreignUuid('issue_warehouse_id')->nullable()->constrained('warehouses');
            $table->foreignUuid('receipt_warehouse_id')->nullable()->constrained('warehouses');

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
        Schema::dropIfExists('movements');
    }
}
