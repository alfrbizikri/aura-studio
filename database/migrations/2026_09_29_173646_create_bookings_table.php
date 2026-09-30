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

            $table->string('booking_code', 30)->unique();

            $table->foreignId('user_id')
                ->constrained('users');

            $table->foreignId('package_id')
                ->constrained('packages');

            $table->unsignedTinyInteger('number_of_people');

            $table->text('location_address')->nullable();
            $table->text('location_notes')->nullable();

            $table->text('notes')->nullable();

            $table->decimal('total_price', 12, 2);

            $table->enum('status', [
                'pending',
                'confirmed',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->timestamps();
            $table->softDeletes();
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
