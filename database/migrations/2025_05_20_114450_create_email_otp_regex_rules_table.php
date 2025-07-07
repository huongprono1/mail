<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('email_otp_regex_rules', function (Blueprint $table) {
            $table->id();
            $table->string('sender_domain');
            $table->string('regex_pattern');
            $table->timestamps();

            $table->index(['sender_domain']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_otp_regex_rules');
    }
};
