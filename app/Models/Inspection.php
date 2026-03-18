<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code_producteur',
        'nom_producteur',
        'code_unique_parcelle',
        'date_inspection',
        'observations',
        'conformite',
        'actions_correctives',
        'gps_inspection',
        'inspecteur',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_inspection' => 'date',
    ];

    /**
     * Get the producer associated with this inspection.
     */
    public function producer()
    {
        return $this->belongsTo(Producer::class, 'code_producteur', 'code_producteur');
    }

    /**
     * Get GPS coordinates as array.
     */
    public function getGpsCoordinatesAttribute()
    {
        return $this->gps_inspection;
    }

    /**
     * Get conformity badge color.
     */
    public function getConformityColorAttribute()
    {
        return match($this->conformite) {
            'Conforme' => 'success',
            'Non conforme' => 'danger',
            'Partiel' => 'warning',
            default => 'secondary',
        };
    }
}
