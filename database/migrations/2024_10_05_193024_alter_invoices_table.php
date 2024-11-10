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
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('client_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->boolean('paid')->default(false);

            $table->integer('value')->default(0);
            $table->enum('value_coin', ['BRL', 'EUR', 'USD', 'GBP'])->default('BRL');

            $table->integer('discount')->default(0);
            $table->enum('discount_coin', ['BRL', 'EUR', 'USD', 'GBP'])->default('BRL');

            $table->string('bank')->nullable();
            $table->string('payment_link')->nullable();
            $table->string('file_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');

            $table->dropColumn('paid');

            $table->dropColumn(['value', 'value_coin']);
            $table->dropColumn(['discount', 'discount_coin']);

            $table->dropColumn('bank');
            $table->dropColumn('payment_link');
            $table->dropColumn('file_path');
        });
    }
};
