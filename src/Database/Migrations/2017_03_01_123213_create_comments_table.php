<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->default(0);
            $table->integer('target_id')->unsigned();
            $table->string('target_type', 255);
            $table->string('name', 255)->nullable()->default(null);
            $table->string('email', 255)->nullable()->default(null);
            $table->text('comment')->nullable()->default(null);
            $table->smallInteger('approved')->unsigned()->default(0);
            $table->integer('parent_id')->unsigned()->default(0);
            $table->enum('seen_by_admin', array('1','0'))->nullable()->default('0');
            $table->integer('quote_id')->unsigned()->default(0);
            $table->integer('created_by')->unsigned()->default(0);
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
        Schema::dropIfExists('comments');
    }
}
