<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_type', function (Blueprint $table) {
            $table->id();

            $table->foreignId('offer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('type_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('cost')->default(0);
            $table->integer('sale')->default(0);

            $table->enum('cost_coin', ['BRL', 'EUR', 'USD', 'GBP'])->default('BRL');
            $table->enum('sale_coin', ['BRL', 'EUR', 'USD', 'GBP'])->default('BRL');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_type');
    }
};
