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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_number');
            $table->string('gateway')->nullable();
            $table->dateTime('transaction_date')->nullable();
            $table->string('account_number')->nullable();
            $table->string('code')->nullable();
            $table->text('content')->nullable();
            $table->string('transfer_type')->nullable();
            $table->decimal('amount');
            $table->decimal('accumulated')->default(0); // Số dư tài khoản (lũy kế)
            $table->string('sub_account')->nullable();
            $table->string('reference_code')->nullable(); // Mã tham chiếu của tin nhắn sms
            $table->text('description')->nullable(); // Toàn bộ nội dung tin nhắn sms
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['transaction_number', 'gateway'], 'unique_transaction_gateway');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
