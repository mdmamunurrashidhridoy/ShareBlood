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
        Schema::create('blood_request_donors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('blood_request_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('donor_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('status', 20)->default('interested');
            // interested | selected | donated | rejected | cancelled

            $table->timestamp('responded_at')->nullable();
            $table->timestamp('selected_at')->nullable();
            $table->timestamp('donated_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->unsignedTinyInteger('bags_donated')->nullable();
            $table->text('note')->nullable();

            $table->foreignId('confirmed_by_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(['blood_request_id', 'donor_user_id']);

            $table->index(['blood_request_id', 'status']);
            $table->index(['donor_user_id', 'status']);
            $table->index(['status', 'donated_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_request_donors');
    }
};
