<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('offers', function (Blueprint $table) {
        $table->id();
        $table->string('title')->nullable(); // title is optional
        $table->string('image'); // image path
        $table->boolean('is_active')->default(true); // active/inactive
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
