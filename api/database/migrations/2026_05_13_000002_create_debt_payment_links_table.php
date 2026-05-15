<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debt_payment_links', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('campaign_id')
                ->constrained('debt_payment_campaigns')
                ->restrictOnDelete();

            $table->string('token', 64)->unique();

            $table->unsignedBigInteger('bitrix_user_id');
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone', 50)->nullable();

            $table->unsignedBigInteger('contract_id')->nullable();
            $table->string('contract_number', 100)->nullable();
            $table->string('contract_guid', 100)->nullable();

            $table->unsignedBigInteger('invoice_id');
            $table->string('invoice_number', 100)->nullable();
            $table->string('invoice_guid', 100)->nullable();

            $table->decimal('amount', 12, 2);
            $table->char('currency', 3)->default('RUB');

            $table->string('status', 30)->default('new');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->unsignedBigInteger('bitrix_order_id')->nullable();
            $table->text('payment_url')->nullable();

            $table->unsignedInteger('reminders_count')->default(0);
            $table->timestamp('last_reminded_at')->nullable();
            $table->timestamp('next_remind_at')->nullable();

            $table->text('last_error')->nullable();
            $table->timestamps();

            $table->unique(['campaign_id', 'invoice_id']);
            $table->index('bitrix_user_id');
            $table->index('invoice_id');
            $table->index('invoice_guid');
            $table->index('bitrix_order_id');
            $table->index(['status', 'created_at']);
            $table->index(['status', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debt_payment_links');
    }
};
