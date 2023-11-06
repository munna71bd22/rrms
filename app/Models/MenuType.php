<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuType extends Model
{
    use HasFactory;
    protected $fillable = ['title','icon'];
    public function menus()
    {
        return $this->hasMany(Menu::class,'type','title')
            ->select(['id', 'title','type','price_before','price','photo']);
    }
}
