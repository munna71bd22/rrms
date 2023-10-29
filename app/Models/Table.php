<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'title',
        'tbl_id',
        'type',
        'room_no',
        'tbl_type',
        'canvas_obj',
        'floor_id'
    ];
    use HasFactory;
}
