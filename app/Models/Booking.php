<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'customer_mobile',
        'email',
        'booking_date',
        'guest_qty',
        'tbl_id',
        'menus',
        'remarks',
        'status',
        'confirmed_by',
        'confirmed_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->select(['id', 'name', 'email', 'mobile', 'avatar']);
    }

    public function tables()
    {
        return Table::whereIn('tbl_id', json_decode($this->tbl_id))->select(['id', 'title']);
    }

    public function menus()
    {
        return Menu::whereIn('title', json_decode($this->menus))->select(['id', 'title']);
    }
    public function conformedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by', 'id')
            ->select(['id', 'name', 'email', 'mobile', 'avatar']);
    }
}
