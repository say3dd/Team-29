<?php
//@noramknarf (Francis Moran) - Everything
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
        Schema::table('baskets', function (Blueprint $table) {
            $table->integer('RAM');
            $table->string('GPU', 255);
            $table->string('processor', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('baskets', function (Blueprint $table) {
            $table->dropcolumn('RAM');
            $table->dropcolumn('GPU');
            $table->dropcolumn('processor');
        });
    }
};
