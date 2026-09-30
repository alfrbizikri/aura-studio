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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('branch_id')
                ->constrained('branches')
                ->cascadeOnDelete();

            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnDelete();

            $table->foreignId('photographer_id')
                ->nullable()
                ->constrained('photographers')
                ->nullOnDelete();

            $table->foreignId('studio_room_id')
                ->nullable()
                ->constrained('studio_rooms')
                ->nullOnDelete();

            $table->date('schedule_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->enum('status', [
                'available',
                'booked',
                'blocked'
            ])->default('available');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
