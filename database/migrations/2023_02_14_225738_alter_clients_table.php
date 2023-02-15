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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('birthday')->nullable();
            $table->string('company_name')->nullable();
            $table->string('website_url')->nullable();
            $table->string('address_country')->nullable();
            $table->string('address_state')->nullable();
            $table->string('address_street_name')->nullable();
            $table->string('address_street_number')->nullable();
            $table->string('address_number')->nullable();
            $table->string('address_zipcode')->nullable();
            $table->string('phone')->nullable();

            $table->string('invoice_company_name')->nullable();
            $table->string('invoice_vat_number')->nullable();
            $table->string('invoice_paypal')->nullable();
            $table->string('invoice_address_country')->nullable();
            $table->string('invoice_address_state')->nullable();
            $table->string('invoice_address_street_name')->nullable();
            $table->string('invoice_address_street_number')->nullable();
            $table->string('invoice_address_number')->nullable();
            $table->string('invoice_address_zipcode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            //
        });
    }
};
