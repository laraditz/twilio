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
        Schema::create('twilio_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('source', 100)->nullable();
            $table->string('sid', 100)->nullable();
            $table->json('request')->nullable();
            $table->json('response')->nullable();
            $table->string('error', 100)->nullable();
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
        Schema::dropIfExists('twilio_logs');
    }
};
