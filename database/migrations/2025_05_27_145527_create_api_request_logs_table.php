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
        Schema::create('api_request_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('method', 10);
            $table->string('path');
            $table->string('route_name')->nullable();
            $table->json('query_params')->nullable();
            $table->json('request_headers')->nullable();
            $table->json('request_body')->nullable();
            $table->integer('status_code')->nullable();
            $table->json('response_headers')->nullable();
            $table->longText('response_content')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->decimal('execution_time', 10, 4)->comment('Execution time in milliseconds');
            $table->text('error_message')->nullable();
            $table->json('additional_data')->nullable();

            $table->timestamps();

            // Indexes for better query performance
            $table->index('user_id');
            $table->index('method');
            $table->index('path');
            $table->index('status_code');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_request_logs');
    }
};
