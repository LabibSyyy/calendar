<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('events', function (Blueprint $table) {
        $table->string('color')->default('lightgreen'); // Set default color
    });
}

public function down()
{
    Schema::table('events', function (Blueprint $table) {
        $table->dropColumn('color'); // Remove color column if needed
    });
}

};
