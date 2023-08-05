<?php

use App\Enums\MovementTypeEnum;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Movement;
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
        Schema::create('movements', function (Blueprint $table) {
            $product = new Product();
            $user = new User();
            $warehouse = new Warehouse();

            $table->id();
            $table->uuid()->unique();
            $table->string('type');
            $table->float('amount', 12, 4);
            $table->float('price', 12, 4);

            $table->foreignUuid($product->getForeignKey())->constrained(
                $product->getTable(),
                $product->getKeyName()
            );

            $table->foreignUuid($user->getForeignKey())->constrained(
                $user->getTable(),
                $user->getKeyName()
            );

            $table->foreignUuid('source_warehouse_uuid')->nullable()->constrained(
                $warehouse->getTable(),
                $warehouse->getKeyName()
            );
            $table->foreignUuid('target_warehouse_uuid')->constrained(
                $warehouse->getTable(),
                $warehouse->getKeyName()
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
        Schema::dropIfExists('movements');
    }
};
