<?php

use App\Models\User;
use App\Models\Warehouse;
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
        Schema::create('discounts', function (Blueprint $table) {
            $user = new User();
            $warehouse = new Warehouse();

            $table->id();
            $table->uuid()->unique();

            $table->float('amount', 12, 3);
            $table->text('note')->nullable();
            $table->foreignUuid($warehouse->getForeignKey())->constrained(
                $warehouse->getTable(), $warehouse->getKeyName()
            );
            $table->foreignUuid($user->getForeignKey())->constrained(
                $user->getTable(), $user->getKeyName()
            );

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
        Schema::dropIfExists('discounts');
    }
};
