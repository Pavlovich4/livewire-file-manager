<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('path');
        });

        // Initialize order for existing folders
        DB::table('folders')->orderBy('id')->get()->each(function ($folder, $index) {
            DB::table('folders')
                ->where('id', $folder->id)
                ->update(['order' => $index]);
        });
    }

    public function down()
    {
        Schema::table('folders', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
