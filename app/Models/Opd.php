<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Opd extends Model
{
    use HasFactory, HasUuids;

    // Set primary key
    protected $primaryKey = 'id';

    // Set key type to string for UUID
    protected $keyType = 'string';

    // UUID is not incrementing
    public $incrementing = false;

    protected $fillable = [
        'level',
        'parent_id',
        'code',
        'name',
        'address',
        'phone',
        'email',
        'head_name',
        'head_nip',
    ];

    protected $casts = [
        'level' => 'integer',
    ];

    /**
     * Get the parent OPD (Dinas).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Opd::class, 'parent_id');
    }

    /**
     * Get all child OPDs (UPTDs).
     */
    public function children(): HasMany
    {
        return $this->hasMany(Opd::class, 'parent_id');
    }

    /**
     * Get the level name.
     */
    public function getLevelNameAttribute(): string
    {
        return match ($this->level) {
            1 => 'Dinas',
            2 => 'UPTD',
            default => 'Unknown',
        };
    }

    /**
     * Get full hierarchy name.
     */
    public function getFullNameAttribute(): string
    {
        if ($this->level == 1) {
            return $this->name;
        }

        return $this->parent
            ? "{$this->parent->name} - {$this->name}"
            : $this->name;
    }

    /**
     * Scope to filter by level.
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope to get Dinas only (level 1).
     */
    public function scopeDinas($query)
    {
        return $query->where('level', 1);
    }

    /**
     * Scope to get UPTD only (level 2).
     */
    public function scopeUptd($query)
    {
        return $query->where('level', 2);
    }

    /**
     * Scope to order by code.
     */
    public function scopeOrderByCode($query)
    {
        return $query->orderByRaw('LENGTH(code) asc')->orderBy('code', 'asc');
    }
}
