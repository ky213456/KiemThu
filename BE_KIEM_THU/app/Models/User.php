<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'nguoi_dung';

    protected $fillable = [
        'ho_ten',
        'email',
        'mat_khau',
        'vai_tro',
        'so_dien_thoai',
        'dia_chi',
        'anh_dai_dien',
        'trang_thai',
        'is_master',
        'id_chuc_vu',
    ];

    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    /**
     * Lay mat khau de xac thuc Laravel Auth
     */
    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    protected function casts(): array
    {
        return [
            'email_xac_thuc_luc' => 'datetime',
            'trang_thai' => 'boolean',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'nguoi_dung_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'nguoi_dung_id');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'nguoi_dung_id');
    }

    public function isAdmin(): bool
    {
        return $this->vai_tro === 'quan_tri';
    }
}
