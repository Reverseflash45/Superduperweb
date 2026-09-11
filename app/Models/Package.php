<?php

namespace App\Models;

use App\Enums\PackageType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'type' => PackageType::class,
            'features' => 'array',
            'price' => 'integer',
            'is_starting_price' => 'boolean',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }

    public function scopeOfType(Builder $query, PackageType $type): Builder
    {
        return $query->where('type', $type);
    }

    /** Label harga siap tampil, mis. "mulai dari Rp 1.500.000". */
    public function getPriceLabelAttribute(): string
    {
        if ($this->price === null) {
            return 'Hubungi kami';
        }

        $formatted = rupiah($this->price);

        return $this->is_starting_price ? "mulai dari {$formatted}" : $formatted;
    }
}
