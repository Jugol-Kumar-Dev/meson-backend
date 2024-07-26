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
        Schema::table('users', function (Blueprint $table) {
//            $table->string('full_phone')->nullable()->after('phone');
//            $table->string('dialCode')->nullable()->after('full_phone');
//            $table->string('iso2')->nullable()->after('dialCode');
//            $table->string('countryName')->nullable()->after('iso2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('full_phone');
            $table->dropColumn('dialCode');
            $table->dropColumn('iso2');
            $table->dropColumn('countryName');
        });
    }
};
