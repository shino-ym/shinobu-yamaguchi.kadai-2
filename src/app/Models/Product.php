<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'img',
        'description',
    ];

    // seasons テーブルとの多対多リレーション
    public function seasons()
{
    return $this->belongsToMany(Season::class, 'product_season', 'product_id', 'season_id');
}

}
