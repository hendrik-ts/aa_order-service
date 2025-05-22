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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->index(); // Add order_code column
            $table->string('table_no', 20); // Add order_code column
            $table->string('outlet_name', 50); // Add order_code column
            $table->decimal('sub_total', 10, 2)->default(0);      // fixed amount discount
            $table->decimal('discount', 10, 2)->default(0);      // fixed amount discount
            $table->decimal('tax', 5, 2)->default(0);       // percent tax rate, e.g. 10.00 for 10%
            $table->decimal('total', 10, 2)->default(0);      // fixed amount discount
            $table->boolean('paid')->default(false);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
