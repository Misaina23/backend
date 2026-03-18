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
        Schema::create('producers', function (Blueprint $table) {
            $table->id();
            $table->string('nom_site');
            $table->string('nom_prenom');
            $table->string('code_producteur')->unique();
            $table->string('telephone')->nullable();
            $table->date('date_integration')->nullable();
            $table->decimal('superficie', 10, 2)->nullable();
            $table->decimal('chiffre_affaires', 15, 2)->nullable();
            $table->string('code_unique_parcelle')->nullable();
            $table->string('culture')->nullable();
            $table->string('interculture')->nullable();
            $table->integer('nombre_arbres')->nullable();
            $table->string('gps_parcelle1')->nullable();
            $table->string('gps_parcelle2')->nullable();
            $table->string('gps_parcelle3')->nullable();
            $table->string('gps_menage')->nullable();
            $table->string('estimation_recolte')->nullable();
            $table->string('rendement')->nullable();
            $table->decimal('quantite_livree', 10, 2)->nullable();
            $table->string('nom_ci')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producers');
    }
};
