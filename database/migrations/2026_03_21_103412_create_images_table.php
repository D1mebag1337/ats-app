<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->smallInteger('ImageID')->autoIncrement()->primary();
            $table->text('Alternativtext')->nullable();
            $table->binary('ImageBinary')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
