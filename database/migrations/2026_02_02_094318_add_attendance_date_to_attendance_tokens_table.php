<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('attendance_tokens', function (Blueprint $table) {
            $table->date('attendance_date')->after('token');
        });
    }

    public function down()
    {
        Schema::table('attendance_tokens', function (Blueprint $table) {
            $table->dropColumn('attendance_date');
        });
    }
};
