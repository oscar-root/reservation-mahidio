<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recu extends Model
{
    // FORCE LE NOM DE LA TABLE (très important en français)
    protected $table = 'recus'; 

    protected $fillable = ['reservation_id', 'nom_client', 'montant', 'date_paiement'];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }
}