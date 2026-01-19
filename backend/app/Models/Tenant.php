<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'is_active',
        'logo',
        'settings',
        'primary_color',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    // get products for the tenant
    public function products() : HasMany{
        return $this->hasMany(Product::class);
    }

    // get categories for the tenant
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    // get orders for the tenant
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // scope to get only active tenants
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    //  find tenant by slug or domain
    public static function findBySlugOrDomain(string $identifier): ?self
    {
        return self::where('slug', $identifier)
            ->orWhere('domain', $identifier)
            ->first();
    }
}