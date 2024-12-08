<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('number')->unique()->nullable();

            $table->foreignId('invoice_id')
                ->nullable()
                ->constrained()
                ->nullOnUpdate();
        });

        Artisan::call('db:seed --class=OrderNumberSeeder');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('number');
            $table->dropForeign('invoice_id');
            $table->dropColumn('invoice_id');
        });
    }
};
