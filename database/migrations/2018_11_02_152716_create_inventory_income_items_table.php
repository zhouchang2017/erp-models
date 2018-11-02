<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryIncomeItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_income_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inventory_income_id')->comment('入库计划id');
            $table->unsignedInteger('product_id')->comment('冗余产品id');
            $table->unsignedInteger('product_variant_id')->comment('变体id');
            $table->unsignedInteger('pcs')->comment('供给数量');
            $table->integer('price')->comment('单价');
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
        Schema::dropIfExists('inventory_income_items');
    }
}
