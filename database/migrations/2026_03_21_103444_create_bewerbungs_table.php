<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bewerbungen', function (Blueprint $table) {
            $table->smallInteger('BewerbungID')->autoIncrement()->primary();
            $table->smallInteger('UserID');
            $table->foreign('UserID')
                  ->references('UserID')
                  ->on('bewerber')
                  ->onDelete('cascade');
            $table->smallInteger('StellenID');
            $table->foreign('StellenID')
                  ->references('StellenID')
                  ->on('stellen')
                  ->onDelete('cascade');
            $table->binary('Anschreiben')->nullable();
            $table->binary('Lebenslauf')->nullable();
            $table->binary('Zeugnisse')->nullable();
            $table->binary('Zertifikate')->nullable();
            $table->boolean('Datenschutzerklaerung')->default(false);
            $table->string('Status', 50)->default('Eingegangen');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bewerbungen');
    }
};
