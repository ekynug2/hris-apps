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
        Schema::dropIfExists('device_logs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
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
    }
};
