<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stellen', function (Blueprint $table) {
            $table->smallInteger('StellenID')->autoIncrement()->primary();
            $table->text('Name');
            $table->text('Beschreibung')->nullable();
            $table->text('Kurzbeschreibung')->nullable();
            $table->json('Aufgaben')->nullable();
            $table->json('Voraussetzungen')->nullable();
            $table->smallInteger('ImageID')->nullable();
            $table->foreign('ImageID')
                  ->references('ImageID')
                  ->on('images')
                  ->onDelete('set null');
            $table->boolean('Online')->default(false);
            $table->text('Arbeitsorte')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stellen');
    }
};
