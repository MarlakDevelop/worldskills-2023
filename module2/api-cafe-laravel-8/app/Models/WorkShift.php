<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShift extends Model
{
    use HasFactory;

    protected $table = 'work_shifts';

    public $timestamps = null;

    protected $fillable = [
        'start',
        'end',
        'active'
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'active' => 'boolean'
    ];

    public function users() {
        return $this->belongsToMany(User::class, 'workers', 'work_shift_id', 'user_id');
    }

    public function orders() {
        return $this->hasMany(Order::class, 'work_shift_id', 'id');
    }

    public function amountForAll() {
        $orders = $this->orders()->get();
        $amount = 0;
        foreach ($orders as $order) {
            $amount += $order->priceAll();
        }
        return $amount;
    }
}
