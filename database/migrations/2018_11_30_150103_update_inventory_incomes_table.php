<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateInventoryIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_incomes', function (Blueprint $table) {
            $table->dropColumn(['confirmed_at', 'shipped_at', 'completed_at','status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_incomes', function (Blueprint $table) {
            $table->unsignedInteger('status')->default(0);
            $table->timestamp('confirmed_at')->nullable()->comment('审核通过时间');
            $table->timestamp('shipped_at')->nullable()->comment('发货时间');
            $table->timestamp('completed_at')->nullable()->comment('入库时间');
        });
    }
}
