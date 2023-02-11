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
        Schema::create('consolidated_rubric_news', function (Blueprint $table) {
            $table->id();
            $table->integer('news_id');
            $table->integer('news_rubric_id');

            $table->softDeletesTz();
            $table->timestamps();

            $table->foreign('news_id')
                ->references('id')
                ->on('news');

            $table->foreign('news_rubric_id')
                ->references('id')
                ->on('news_rubrics');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consolidated_rubric_news');
    }
};
