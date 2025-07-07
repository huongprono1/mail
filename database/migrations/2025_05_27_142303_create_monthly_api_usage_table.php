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
        Schema::create('monthly_api_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('year');
            $table->unsignedInteger('month');
            $table->unsignedBigInteger('count')->default(0);
            $table->unsignedBigInteger('limit')->default(1000); // Default limit of 1000 requests per month
            $table->timestamps();

            // Add unique constraint for user_id, year, and month
            $table->unique(['user_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_api_usage');
    }
};
