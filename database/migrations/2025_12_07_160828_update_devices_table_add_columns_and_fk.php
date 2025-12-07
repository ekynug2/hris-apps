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
            $table->string('alias')->nullable()->after('ip_address')->comment('Device Name');
            $table->string('terminal_name')->nullable()->after('alias');
            $table->string('fw_ver')->nullable()->after('terminal_name');
            $table->integer('state')->default(1)->after('fw_ver');
            $table->integer('terminal_tz')->nullable()->default(8)->after('state'); // Time Zone
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['department_id', 'alias', 'terminal_name', 'fw_ver', 'state', 'terminal_tz']);
        });
    }
};
