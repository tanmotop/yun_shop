<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImsYzWechatNewsReplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('yz_wechat_news_reply')) {
            Schema::create('yz_wechat_news_reply', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('rid')->default(0);
                $table->integer('parent_id')->default(0);
                $table->string('title',50)->default('');  
                $table->string('author',64)->default('');  
                $table->string('description',255)->default('');  
                $table->string('thumb',500)->default('');  
                $table->mediumText('content')->nullable();
                $table->string('url',300)->default('');
                $table->integer('displayorder')->default(0);
                $table->integer('incontent')->default(0);
                $table->integer('createtime')->default(0);
                $table->string('media_id',255)->default('');
                $table->integer('created_at')->nullable();
                $table->integer('updated_at')->nullable();
                $table->integer('deleted_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yz_wechat_news_reply');
    }
}
