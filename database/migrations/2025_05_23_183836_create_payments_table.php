<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('asaas_id')->nullable();
            $table->string('customer')->nullable();
            $table->string('billing_type')->nullable();
            $table->date('due_date')->nullable();
            $table->float('value')->nullable();
            $table->integer('installment')->nullable();
            $table->string('installment_token')->nullable();
            $table->string('description')->nullable();
            $table->string('bank_slip_url')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
