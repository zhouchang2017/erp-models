<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryExpendItemUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_expend_item_units', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id')->comment('拆单对象');
            $table->unsignedInteger('shipment_track_id')->comment('物流信息');
            $table->integer('adjustments_total')->default(0)->comment('优惠调整');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_expend_item_units');
    }
}
