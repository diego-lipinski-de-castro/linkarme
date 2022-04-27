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

            $table->string('url');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('obs')->nullable();

            $table->integer('da')->nullable();
            $table->integer('dr')->nullable();
            $table->integer('traffic')->nullable();
            $table->integer('tf')->nullable();
            $table->string('language')->nullable();
            $table->string('country')->nullable();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->enum('link_type', ['NOFOLLOW', 'DOFOLLOW']);

            $table->boolean('gambling')->default(false);
            $table->boolean('cdb')->default(false);
            $table->boolean('cripto')->default(false);
            $table->boolean('sponsor')->default(false);
            $table->boolean('ssl')->default(false);
            $table->boolean('broken')->default(false);
            
            $table->string('cost')->nullable();
            $table->string('sale')->nullable();

            $table->timestamps();
            $table->date('last_posted')->nullable();
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
