<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
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
    ];

    protected $casts = [
        'level' => 'integer',
    ];

    /**
     * Get the parent region.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'parent_id');
    }

    /**
     * Get all child regions.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Region::class, 'parent_id');
    }

    /**
     * Get the level name.
     */
    public function getLevelNameAttribute(): string
    {
        return match ($this->level) {
            1 => 'Province',
            2 => 'City/Regency',
            3 => 'District',
            4 => 'Village',
            default => 'Unknown',
        };
    }

    /**
     * Get full hierarchy name.
     */
    public function getFullNameAttribute(): string
    {
        $names = [$this->name];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($names, $parent->name);
            $parent = $parent->parent;
        }

        return implode(', ', $names);
    }

    /**
     * Scope to filter by level.
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope to get provinces (level 1).
     */
    public function scopeProvinces($query)
    {
        return $query->where('level', 1);
    }

    /**
     * Scope to get cities (level 2).
     */
    public function scopeCities($query)
    {
        return $query->where('level', 2);
    }

    /**
     * Scope to get districts (level 3).
     */
    public function scopeDistricts($query)
    {
        return $query->where('level', 3);
    }

    /**
     * Scope to get villages (level 4).
     */
    public function scopeVillages($query)
    {
        return $query->where('level', 4);
    }
}
