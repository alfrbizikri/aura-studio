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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_id')
                ->constrained('services')
                ->cascadeOnDelete();

            $table->string('name', 100);
            $table->text('description')->nullable();

            $table->decimal('price', 12, 2);

            $table->unsignedSmallInteger('duration_minutes');
            $table->unsignedSmallInteger('max_people')->nullable();
            $table->unsignedSmallInteger('photo_count')->nullable();

            $table->text('package_includes')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
