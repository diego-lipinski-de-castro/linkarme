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
        Schema::create('order_item', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('site_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('link')->nullable();

            $table->integer('cost')->default(0);
            $table->integer('sale')->default(0);

            $table->enum('cost_coin', ['BRL', 'EUR', 'USD', 'GBP'])->default('BRL');
            $table->enum('sale_coin', ['BRL', 'EUR', 'USD', 'GBP'])->default('BRL');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item');
    }
};
