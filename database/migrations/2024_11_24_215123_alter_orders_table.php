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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('type_id')
                ->nullable()
                ->constrained();

            $table->dropColumn('url');

            $table->dropColumn('charged');
            $table->dropColumn('paid');
            $table->dropColumn('markup');
            $table->dropColumn('comission');

            $table->dropForeign(['site_id']);
            $table->dropForeign(['seller_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_type_id_foreign');
            $table->dropColumn('type_id');
        });
    }
};
