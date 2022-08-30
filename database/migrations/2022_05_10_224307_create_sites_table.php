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
        Schema::create('sites', function (Blueprint $table) {
            $table->id();

            $table->string('url')->unique();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            
            $table->text('obs')->nullable();
            $table->text('admin_obs')->nullable();

            $table->unsignedInteger('da')->nullable();
            $table->unsignedInteger('dr')->nullable();
            $table->unsignedBigInteger('traffic')->nullable();
            $table->unsignedInteger('tf')->nullable();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            
            $table->foreignId('language_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('country_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->enum('link_type', ['NOFOLLOW', 'DOFOLLOW'])->default('DOFOLLOW');

            $table->boolean('gambling')->default(false);
            $table->boolean('cdb')->default(false);
            $table->boolean('cripto')->default(false);
            $table->boolean('sponsor')->default(false);
            
            $table->boolean('broken')->nullable();
            $table->boolean('ssl')->nullable();
            
            $table->integer('cost')->nullable();
            $table->integer('sale')->nullable();

            $table->enum('cost_coin', ['BRL', 'EUR', 'USD'])->default('BRL');
            $table->enum('sale_coin', ['BRL', 'EUR', 'USD'])->default('BRL');
            
            $table->date('last_posted')->nullable();

            $table->date('inserted_at');
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
        Schema::dropIfExists('sites');
    }
};
