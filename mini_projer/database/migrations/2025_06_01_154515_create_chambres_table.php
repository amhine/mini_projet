<?php

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
       Schema::create('chambres', function (Blueprint $table) {
                $table->id();
                $table->integer('numero')->unique();
                $table->decimal('tarif_journalier', 10, 2);
                $table->integer('capacite');
                $table->enum('type', ['standard', 'suite']); 
                $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chambres');
    }
};
