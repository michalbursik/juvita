<?php

use App\Models\Warehouse;
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
            $table->id();

            $table->string('type')->default(Movement::TYPE_ISSUE);
            $table->float('amount', 8, 1);
            $table->float('price', 8, 1)->nullable();

            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('issue_warehouse_id')->nullable()->constrained('warehouses');
            $table->foreignId('receipt_warehouse_id')->nullable()->constrained('warehouses');

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
