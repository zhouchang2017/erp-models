<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryExpendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_expends', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description')->nullable();
            $table->unsignedInteger('pcs')->default(0)->comment('总计数量');
            $table->unsignedInteger('price')->default(0)->comment('总计金额');
            $table->unsignedInteger('status')->default(0);
            $table->morphs('expendable');
            $table->unsignedInteger('warehouse_id')->comment('仓库id');
            $table->timestamp('confirmed_at')->nullable()->comment('审核通过时间');
            $table->timestamp('shipped_at')->nullable()->comment('发货时间');
            $table->timestamp('completed_at')->nullable()->comment('出库时间');
            $table->boolean('has_shipment')->default(true)->comment('是否需要物流');
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
        Schema::dropIfExists('inventory_expends');
    }
}
