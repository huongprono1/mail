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
        // set currency null before adding new columns
        try {
            Schema::table('plans', function (Blueprint $table) {
                $table->dropColumn(['currency']);
            });
        } catch (\Exception $e) {
            // Ignore if the column does not exist
        }

        Schema::table('plans', function (Blueprint $table) {
            $table->string('key')->index()->nullable()->unique()->after('id');
            $table->json('month_price')->nullable();
            $table->json('year_price')->nullable();
            $table->json('currency')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['key', 'month_price', 'year_price']);
        });
    }
};
