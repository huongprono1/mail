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
        try {
            Schema::table('clients', function (Blueprint $table) {
                $table->string('country')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
            });
        } catch (\Exception $exception) {
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
