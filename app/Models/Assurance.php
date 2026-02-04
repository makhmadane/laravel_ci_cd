<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assurance extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'numero_police',
        'type_id',
        'montant_prime',
        'statut',
    ];

    protected $casts = [
        'montant_prime' => 'decimal:2',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
}
