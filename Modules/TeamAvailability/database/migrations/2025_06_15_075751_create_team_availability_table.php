<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('team_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->tinyInteger('day_of_week')->comment('0=Sunday, 1=Monday, ..., 6=Saturday');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['team_id', 'day_of_week']);
            $table->index(['team_id', 'is_active']);
        });

        DB::statement("ALTER TABLE team_availability ADD CONSTRAINT chk_day_of_week CHECK (day_of_week BETWEEN 0 AND 6)");
        DB::statement("ALTER TABLE team_availability ADD CONSTRAINT chk_time_order CHECK (start_time < end_time)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_availability');
    }
};
