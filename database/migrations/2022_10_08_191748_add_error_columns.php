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
        Schema::table('twilio_logs', function (Blueprint $table) {
            $table->text('error_message')->nullable()->after('error_code');

            $table->index('sid');
        });

        Schema::table('twilio_messages', function (Blueprint $table) {
            $table->text('error_message')->nullable()->after('sent_at');

            $table->index('sid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('twilio_logs', function (Blueprint $table) {
            $table->dropColumn('error_message');
        });

        Schema::table('twilio_messages', function (Blueprint $table) {
            $table->dropColumn('error_message');
        });
    }
};
