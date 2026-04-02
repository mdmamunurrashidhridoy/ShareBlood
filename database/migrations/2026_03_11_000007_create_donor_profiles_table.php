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
        Schema::create("donor_profiles", function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId("user_id")
                ->constrained()
                ->cascadeOnDelete()
                ->unique();

            $table->boolean("is_available")->default(true);

            $table->date("last_donate_date")->nullable();
            $table->date("next_eligible_date")->nullable();

            $table->text("note")->nullable();

            $table->timestamps();

            $table->index(["is_available", "next_eligible_date"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("donor_profiles");
    }
};
