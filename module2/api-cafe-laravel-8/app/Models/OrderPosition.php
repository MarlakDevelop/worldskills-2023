<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPosition extends Model
{
    use HasFactory;

    protected $table = 'orders_positions';

    protected $fillable = [
        'order_id',
        'position_id',
        'count'
    ];

    public $timestamps = null;

    public function order() {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function position() {
        return $this->hasOne(Position::class, 'id', 'position_id');
    }

    public function price() {
        return $this->position->price * $this->count;
    }
}
