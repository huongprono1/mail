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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique(); // Unique session identifier stored in cookies
            $table->string('ip_address')->nullable(); // IP address of the user
            $table->string('user_agent')->nullable(); // User agent string
            $table->json('additional_info')->nullable();
            $table->timestamps();
        });

        Schema::create('client_mail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('mail_id')->constrained('mails')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_mail');
        Schema::dropIfExists('clients');
    }
};
