<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwsMediaConversionLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aws_media_conversions', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('model');
            $table->string('job_id')->nullable();
            $table->json('message')->nullable();
            $table->string('status')->nullable();
            $table->integer('progress')->nullable();
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
        Schema::dropIfExists('aws_media_conversions');
    }
}
