<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'tenant_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'b2b_price',
        'stock',
        'sku',
        'images',
        'is_active',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'b2b_price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
        'images' => 'array',
    ];

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'sku' => $this->sku,
            'price' => (float) $this->price,
            'category' => $this->category?->name,
            'tenant_id' => $this->tenant_id,
            'is_active' => $this->is_active,
        ];
    }

    /**
     * Determine if the model should be searchable.
     */
    public function shouldBeSearchable(): bool
    {
        return $this->is_active;
    }

    /**
     * Get the tenant that owns the product
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the category that owns the product
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope a query to only include active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query for specific tenant
     */
    public function scopeForTenant($query, int $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Check if product is in stock
     */
    public function inStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Get price based on customer type
     */
    public function getPriceForCustomerType(string $type = 'b2c'): float
    {
        return $type === 'b2b' && $this->b2b_price 
            ? (float) $this->b2b_price 
            : (float) $this->price;
    }
}