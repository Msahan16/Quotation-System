<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->date('date');
            $table->string('quotation_number')->unique();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('fixed_charge', 10, 2)->default(0);
            $table->decimal('transport_charge', 10, 2)->default(0);
            $table->decimal('additional_amount', 10, 2)->default(0);
            $table->text('additional_notes')->nullable();
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
