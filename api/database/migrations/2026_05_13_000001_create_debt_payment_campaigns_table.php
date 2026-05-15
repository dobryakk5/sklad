<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debt_payment_campaigns', function (Blueprint $table): void {
            $table->id();
            $table->string('source', 50)->default('monthly_debt');
            $table->date('campaign_date');
            $table->string('status', 30)->default('draft');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('last_error')->nullable();
            $table->timestamps();

            $table->unique(['source', 'campaign_date']);
            $table->index(['status', 'campaign_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debt_payment_campaigns');
    }
};
