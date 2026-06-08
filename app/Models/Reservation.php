<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    protected $fillable = [
        'client_id', 'nom_agent', 'postnom_agent', 'matricule_agent', 
        'date_engagement', 'type_ceremonie', 'date_debut', 'duree', 
        'motif', 'statut', 'montant_total'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function recu(): HasOne
    {
        return $this->hasOne(Recu::class, 'reservation_id');
    }
}