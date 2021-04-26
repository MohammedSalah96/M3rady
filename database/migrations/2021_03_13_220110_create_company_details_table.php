<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_id',400)->unique();
            $table->string('name_ar',400);
            $table->string('name_en', 400);
            $table->text('description', 400);
            $table->integer('available_free_posts');
            $table->string('lat', 20);
            $table->string('lng', 20);
            $table->string('whatsapp', 500);
            $table->string('facebook', 500);
            $table->string('twitter', 500);
            $table->string('website', 500);
            $table->boolean('allowed_to_rate')->default(true);
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('company_details');
    }
}
