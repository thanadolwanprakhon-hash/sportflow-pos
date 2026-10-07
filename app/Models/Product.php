<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'category_id',
        'sku',
        'barcode',
        'name',
        'image_url',
        'source_url',
        'price',
        'cost',
        'stock',
        'minimum_stock',
        'is_active',
    ];
    protected function casts(): array { return ['price' => 'decimal:2', 'cost' => 'decimal:2', 'is_active' => 'boolean']; }
    public function category() { return $this->belongsTo(Category::class); }
    public function saleItems() { return $this->hasMany(SaleItem::class); }
    public function stockMovements() { return $this->hasMany(StockMovement::class); }
    public function scopeLowStock($query) { return $query->whereColumn('stock', '<=', 'minimum_stock'); }
}
