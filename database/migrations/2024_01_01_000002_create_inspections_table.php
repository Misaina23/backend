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
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->string('code_producteur');
            $table->string('nom_producteur');
            $table->string('code_unique_parcelle')->nullable();
            $table->date('date_inspection');
            $table->text('observations')->nullable();
            $table->enum('conformite', ['Conforme', 'Non conforme', 'Partiel'])->nullable();
            $table->text('actions_correctives')->nullable();
            $table->string('gps_inspection')->nullable();
            $table->string('inspecteur')->nullable();
            $table->timestamps();

            $table->index('code_producteur');
            $table->index('date_inspection');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
