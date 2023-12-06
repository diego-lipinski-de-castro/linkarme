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
            $table->string('owner_role')->nullable();
            $table->boolean('promo')->default(false);
            $table->boolean('restrict')->default(false);
            $table->string('restrict_detail')->nullable();
            $table->boolean('archive_article')->default(false);
            $table->string('archive_due')->nullable();
            $table->integer('links')->nullable();
            $table->boolean('embed')->default(false);
            $table->integer('images')->nullable();
            $table->string('example_article')->nullable();
            $table->text('rules')->nullable();
            $table->boolean('google_news')->default(false);
            $table->string('global_account')->nullable();
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
            $table->dropColumn('owner_role');
            $table->dropColumn('promo');
            $table->dropColumn('restrict');
            $table->dropColumn('restrict_detail');
            $table->dropColumn('archive_article');
            $table->dropColumn('archive_due');
            $table->dropColumn('links');
            $table->dropColumn('embed');
            $table->dropColumn('images');
            $table->dropColumn('example_article');
            $table->dropColumn('rules');
            $table->dropColumn('google_news');
            $table->dropColumn('global_account');
        });
    }
};
