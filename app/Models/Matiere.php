<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matiere extends Model
{
    protected $fillable = ['code', 'libelle', 'coefficient', 'semestre', 'classe_id', 'enseignant_id'];

    protected function casts(): array
    {
        return [
            'coefficient' => 'integer',
            'semestre' => 'integer',
        ];
    }

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class);
    }

    public function enseignant(): BelongsTo
    {
        return $this->belongsTo(Enseignant::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
