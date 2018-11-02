<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable()->comment('供应商代码')->unique();
            $table->unsignedTinyInteger('level')->default(0)->comment('供应商等级');
            $table->text('description')->nullable()->comment('供应商描述说明');
            $table->string('email')->nullable()->comment('电子邮箱');
            $table->string('qq')->nullable()->comment('qq');
            $table->string('wechat')->nullable()->comment('微信');
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}
