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
        Schema::create('twilio_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('sid', 100);
            $table->smallInteger('direction')->nullable();
            $table->string('from', 50);
            $table->string('to', 50);
            $table->text('body')->nullable();
            $table->smallInteger('type')->nullable();
            $table->smallInteger('status')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->string('account_sid', 100)->nullable();
            $table->string('messaging_service_sid', 100)->nullable();
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
        Schema::dropIfExists('twilio_messages');
    }
};
