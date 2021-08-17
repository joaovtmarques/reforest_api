<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('bio')->nullable();
            $table->string('avatar')->default('default.png');
            $table->string('permission')->default('user');
            $table->timestamps();
        });
        Schema::create('greencredits', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->integer('amountgc')->default(0);
        });
        Schema::create('awards', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->integer('costcv');
            $table->string('award_name');
            $table->string('award_avatar');
            $table->string('description');
        });
        Schema::create('information', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->string('street');
            $table->string('district');
            $table->string('zipcode');
            $table->string('city');
            $table->string('state');
        });
        Schema::create('greenpoints', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->string('name');
            $table->string('point_avatar');
            $table->string('description');
        });
        Schema::create('infogreenpoints', function (Blueprint $table) {
            $table->id();
            $table->integer('id_greenpoint');
            $table->string('street');
            $table->string('district');
            $table->string('zipcode');
            $table->string('city');
            $table->string('state');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
        });
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->integer('id_greenpoint');
        });
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->string('text');
            $table->string('image');
            $table->timestamps();
        });
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->integer('id_post');
            $table->integer('id_user');
        });
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->integer('id_post');
            $table->integer('id_user');
            $table->string('comment');
        });
        Schema::create('exchanges', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->integer('id_award');
            $table->timestamps();
        });
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->integer('award_value');
            $table->string('name');
            $table->string('description');
        });
        Schema::create('infomissions', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->integer('id_mission');
            $table->boolean('complete');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('greencredits');
        Schema::dropIfExists('awards');
        Schema::dropIfExists('information');
        Schema::dropIfExists('greenpoints');
        Schema::dropIfExists('infogreenpoints');
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('likes');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('exchanges');
        Schema::dropIfExists('missions');
        Schema::dropIfExists('infomissions');
    }
}
