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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('site_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('client_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('seller_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('url');
            
            $table->boolean('broken')->nullable();
            $table->boolean('ssl')->nullable();

            $table->date('receipt_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->date('payment_date')->nullable();

            $table->integer('charged')->nullable();
            $table->integer('paid')->nullable();
            $table->integer('markup')->nullable();
            $table->integer('comission')->nullable();    

            $table->enum('status', ['WAITING', 'PRODUCTION', 'SUBMITTED', 'PROCESSING', 'PUBLISHED', 'INVOICE', 'COMPLETED']);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
