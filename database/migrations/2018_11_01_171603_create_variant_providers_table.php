<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariantProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variant_providers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_variant_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('price')->default(0)->comment('报价');
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
        Schema::dropIfExists('variant_providers');
    }
}
