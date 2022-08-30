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
        Schema::table('sites', function (Blueprint $table) {
            $table->boolean('menu')->nullable();
            $table->boolean('banner')->nullable();

            $table->string('owner_name')->nullable();
            $table->string('owner_email')->nullable();
            $table->string('owner_phone')->nullable();

            $table->text('bank')->nullable();
            $table->string('pix')->nullable();

            $table->string('phone')->nullable();
            $table->string('paypal')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('menu');
            $table->dropColumn('banner');

            $table->dropColumn('owner_name');
            $table->dropColumn('owner_email');
            $table->dropColumn('owner_phone');

            $table->dropColumn('bank');
            $table->dropColumn('pix');

            $table->dropColumn('phone');
            $table->dropColumn('paypal');
            $table->dropColumn('instagram');
            $table->dropColumn('facebook');
        });
    }
};
