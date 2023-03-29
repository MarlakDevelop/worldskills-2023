<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Worker extends Pivot
{
    use HasFactory;

    protected $table = 'workers';

    public $timestamps = null;

    protected $fillable = [
        'user_id',
        'work_shift_id'
    ];
}
