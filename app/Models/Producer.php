<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom_site',
        'nom_prenom',
        'code_producteur',
        'telephone',
        'date_integration',
        'superficie',
        'chiffre_affaires',
        'code_unique_parcelle',
        'culture',
        'interculture',
        'nombre_arbres',
        'gps_parcelle1',
        'gps_parcelle2',
        'gps_parcelle3',
        'gps_menage',
        'estimation_recolte',
        'rendement',
        'quantite_livree',
        'nom_ci',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_integration' => 'date',
        'superficie' => 'decimal:2',
        'chiffre_affaires' => 'decimal:2',
        'quantite_livree' => 'decimal:2',
        'nombre_arbres' => 'integer',
    ];

    /**
     * Get the inspections for this producer.
     */
    public function inspections()
    {
        return $this->hasMany(Inspection::class, 'code_producteur', 'code_producteur');
    }

    /**
     * Get the GPS coordinates as array.
     */
    public function getGpsCoordinatesAttribute()
    {
        return [
            'parcelle1' => $this->gps_parcelle1,
            'parcelle2' => $this->gps_parcelle2,
            'parcelle3' => $this->gps_parcelle3,
            'menage' => $this->gps_menage,
        ];
    }
}
