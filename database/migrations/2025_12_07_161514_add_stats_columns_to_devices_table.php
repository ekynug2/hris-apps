<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->integer('user_count')->default(0)->after('terminal_tz');
            $table->integer('fp_count')->default(0)->after('user_count');
            $table->integer('face_count')->default(0)->after('fp_count');
            $table->integer('palm_count')->default(0)->after('face_count');
            $table->integer('transaction_count')->default(0)->after('palm_count');
            $table->integer('cmd_count')->default(0)->after('transaction_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn([
                'user_count',
                'fp_count',
                'face_count',
                'palm_count',
                'transaction_count',
                'cmd_count'
            ]);
        });
    }
};
