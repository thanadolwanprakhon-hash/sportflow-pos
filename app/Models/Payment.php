<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['sale_id', 'method', 'amount', 'received_amount', 'change_amount', 'reference', 'status', 'paid_at'];
    protected function casts(): array { return ['amount' => 'decimal:2', 'received_amount' => 'decimal:2', 'change_amount' => 'decimal:2', 'paid_at' => 'datetime']; }
    public function sale() { return $this->belongsTo(Sale::class); }
}
