<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShortLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('short_links', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('view_count')->index();
            $table->string('link_code', 50)->unique();
            $table->string('link_redirect', 500);
            $table->integer('user_id')->index();
            $table->tinyInteger('status')->index();
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
        Schema::dropIfExists('short_links');
    }
}
