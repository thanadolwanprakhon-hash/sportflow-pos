<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = ['product_id', 'sale_id', 'user_id', 'type', 'quantity', 'balance_after', 'note'];
    public function product() { return $this->belongsTo(Product::class); }
}
