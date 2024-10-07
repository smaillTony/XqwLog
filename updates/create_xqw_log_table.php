<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXqwLogTable extends Migration
{
    // 这里可以指定你的数据库连接
    public function getConnection()
    {
        return config('database.connection') ?: config('database.default');
    }

    public function up()
    {
        if (! Schema::hasTable('admin_xqw_log')) {
            Schema::create('admin_xqw_log', function (Blueprint $table) {
                $table->bigIncrements('id')->unsigned();
                $table->bigInteger('user_id');
                $table->string('path');
                $table->string('method', 10);
                $table->string('ip');
                $table->text('input');
                $table->index('user_id');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('admin_xqw_log');
    }
}
