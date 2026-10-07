<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;
    protected $fillable = ['member_code', 'name', 'phone', 'email', 'points', 'total_spent', 'tier', 'joined_at'];
    protected function casts(): array { return ['joined_at' => 'date', 'total_spent' => 'decimal:2']; }
    public function sales() { return $this->hasMany(Sale::class); }
}
