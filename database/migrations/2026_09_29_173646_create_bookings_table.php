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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('branch_id')
                ->constrained('branches');

            $table->foreignId('package_id')
                ->constrained('packages');

            $table->foreignId('photographer_id')
                ->nullable()
                ->constrained('photographers')
                ->nullOnDelete();

            $table->foreignId('studio_room_id')
                ->nullable()
                ->constrained('studio_rooms')
                ->nullOnDelete();

            $table->string('booking_code')->unique();

            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->integer('number_of_people')->nullable();

            $table->decimal('total_price', 12, 2);

            $table->enum('status', [
                'pending',
                'confirmed',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
