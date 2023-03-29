<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'users';

    public $timestamps = null;

    public const ROLES = [
        'admin' => 1,
        'cook' => 2,
        'waiter' => 3,
    ];

    public const STATUSES = [
        'created' => 'created',
        'added' => 'added',
        'working' => 'working',
        'rest' => 'rest',
        'dismissal' => 'dismissal'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'login',
        'password',
        'photo_file',
        'role_id',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    public function role() {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function isAdmin() {
        return $this->role_id === self::ROLES['admin'];
    }

    public function isCook() {
        return $this->role_id === self::ROLES['cook'];
    }

    public function isWaiter() {
        return $this->role_id === self::ROLES['waiter'];
    }
}
