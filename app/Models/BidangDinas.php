<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BidangDinas extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $primaryKey = 'id';
    // Set key type to string for UUID
    protected $keyType = 'string';

    // UUID is not incrementing
    public $incrementing = false;

    protected $table = 'bidang_dinas';

    protected $fillable = [
        'opd_id',
        'parent_id',
        'level',
        'name',
        'abbreviation',
    ];

    protected $casts = [
        'level' => 'integer',
    ];

    // ============ RELATIONS ============

    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(BidangDinas::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(BidangDinas::class, 'parent_id');
    }

    // ============ SCOPES ============

    public function scopeByLevel($query, int $level)
    {
        return $query->where('level', $level);
    }

    public function scopeByOpd($query, string $opdId)
    {
        return $query->where('opd_id', $opdId);
    }

    // ============ ACCESSORS ============

    public function getLevelLabelAttribute(): string
    {
        return match ($this->level) {
            1 => 'Bidang',
            2 => 'Sub Bidang / Sub Bagian',
            default => 'Tidak Diketahui',
        };
    }
}
