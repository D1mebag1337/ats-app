<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bewerber', function (Blueprint $table) {
            $table->smallInteger('UserID')->primary();
            $table->foreign('UserID')
                  ->references('UserID')
                  ->on('users')
                  ->onDelete('cascade');
            $table->text('Name');
            $table->text('Vorname');
            $table->date('Gebdatum')->nullable();
            $table->text('Strasse')->nullable();
            $table->text('Hausnr')->nullable();
            $table->smallInteger('Plz')->nullable();
            $table->text('Ort')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bewerber');
    }
};
