<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('Companies_related_countries', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('Company_id')->unsigned();
            $table->string('related_country');

            $table->timestamps();
        });
        Schema::table('Companies_related_countries', function(Blueprint $table)
        {
            $table->foreign('Company_id')->references('id')->on('Companies')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Companies_related_countries');
    }
};
