<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rut',
        'cost',
        'price',
        'stock',
        'alerts',
        'category_id',
        'imagex '
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getImagenAttribute()
    {
        if ($this->image != null)
        {
            return (file_exists('storage/products/' . $this->image) ? $this->image : 'noimg.jpg');
        }
        else
        {
            return 'noimg.jpg';
        }
    }
}
