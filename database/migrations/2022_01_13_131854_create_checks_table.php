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
        Schema::create('checks', function (Blueprint $table) {
            $warehouse = new Warehouse();
            $user = new User();

            $table->id();
            $table->uuid()->unique();

            $table->float('discount', 12, 4)->default(0);
            $table->float('total_price', 12, 4)->nullable();

            $table->foreignUuid($warehouse->getForeignKey())->constrained(
                $warehouse->getTable(), $warehouse->getKeyName()
            );
            $table->foreignUuid($user->getForeignKey())->constrained(
                $user->getTable(), $user->getKeyName()
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
        Schema::dropIfExists('checks');
    }
};
