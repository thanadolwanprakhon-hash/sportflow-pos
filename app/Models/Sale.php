<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['invoice_number', 'member_id', 'employee_id', 'subtotal', 'discount', 'total', 'status', 'sold_at'];
    protected function casts(): array { return ['subtotal' => 'decimal:2', 'discount' => 'decimal:2', 'total' => 'decimal:2', 'sold_at' => 'datetime']; }
    public function member() { return $this->belongsTo(Member::class); }
    public function employee() { return $this->belongsTo(User::class, 'employee_id'); }
    public function items() { return $this->hasMany(SaleItem::class); }
    public function payment() { return $this->hasOne(Payment::class); }
}
