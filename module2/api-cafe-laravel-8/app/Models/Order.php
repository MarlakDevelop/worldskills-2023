<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    public const STATUSES = [
        'accepted' => 'Принят',
        'cooking' => 'preparing',
        'ready' => 'Готов',
        'paid-up' => 'paid-up',
        'canceled' => 'Отменен'
    ];

    const CREATED_AT = 'create_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'table_id',
        'shift_worker_id',
        'work_shift_id',
        'status',
        'number_of_person'
    ];

    public function positions() {
        return $this->hasMany(OrderPosition::class, 'order_id', 'id');
    }

    public function table() {
        return $this->hasOne(Table::class, 'id', 'table_id');
    }

    public function worker() {
        return $this->hasOne(User::class, 'id', 'shift_worker_id');
    }

    public function workShift() {
        return $this->hasOne(WorkShift::class, 'id', 'work_shift_id');
    }

    public function priceAll() {
        $positions = $this->positions()->get();
        $total = 0;
        foreach ($positions as $position) {
            $total += $position->price();
        }
        return $total;
    }
}
