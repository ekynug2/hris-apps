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
        // Biometric Templates (Fingerprint, Face, etc.)
        Schema::create('bio_templates', function (Blueprint $table) {
            $table->id();
            $table->string('employee_nik')->index();
            $table->integer('type'); // 1=FP, 9=Face
            $table->integer('no'); // Index
            $table->integer('size')->nullable();
            $table->integer('valid')->default(1);
            $table->longText('content');
            $table->string('version')->nullable();
            $table->string('device_sn')->nullable();
            $table->timestamps();
        });

        // Device Operation Logs
        Schema::create('device_logs', function (Blueprint $table) {
            $table->id();
            $table->string('device_sn')->index();
            $table->string('operator')->nullable(); // NIK
            $table->dateTime('performed_at');
            $table->integer('type'); // OpType ID
            $table->string('value1')->nullable();
            $table->string('value2')->nullable();
            $table->timestamps();
        });

        // Add Photo columns if not exist
        if (!Schema::hasColumn('employees', 'photo_path')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->string('photo_path')->nullable()->after('email');
            });
        }

        if (!Schema::hasColumn('attendances', 'photo_path')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->string('photo_path')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bio_templates');
        Schema::dropIfExists('device_logs');

        if (Schema::hasColumn('employees', 'photo_path')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('photo_path');
            });
        }

        if (Schema::hasColumn('attendances', 'photo_path')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->dropColumn('photo_path');
            });
        }
    }
};
