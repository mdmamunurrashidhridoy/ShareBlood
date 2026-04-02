<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('requester_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('requester_name', 150);
            $table->string('requester_phone', 11);

            $table->string('patient_name', 150);
            $table->string('blood_group', 5);

            $table->unsignedTinyInteger('quantity_bags')->nullable();

            $table->date('needed_date');
            $table->boolean('is_emergency')->default(false);

            $table
                ->foreignId('division_id')
                ->constrained()
                ->cascadeOnDelete();
            $table
                ->foreignId('district_id')
                ->constrained()
                ->cascadeOnDelete();
            $table
                ->foreignId('upazilla_id')
                ->nullable()
                ->constrained() 
                ->cascadeOnDelete();
            $table
                ->foreignId('city_corporation_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table
                ->foreignId('city_area_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table
                ->string('hospital_name', 150)
                ->nullable();
            $table
                ->string('address_line', 255)
                ->nullable();

            $table
                ->text('note')->nullable();

            $table->string('status')->default('pending'); // pending|accepted|completed|cancelled|expired
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();

            $table->index(['blood_group', 'status', 'needed_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
