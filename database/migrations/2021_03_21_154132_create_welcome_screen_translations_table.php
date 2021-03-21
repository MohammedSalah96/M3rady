<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWelcomeScreenTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('welcome_screen_translations', function (Blueprint $table) {
            $table->id();
            $table->char('locale', 2);
            $table->text('description');
            $table->bigInteger('welcome_screen_id')->unsigned();
            $table->foreign('welcome_screen_id')->references('id')->on('welcome_screens');
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
        Schema::dropIfExists('welcome_screen_translations');
    }
}
