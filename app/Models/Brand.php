<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug', // ✅ Ajout ici
        'image',
        // Ajoute d'autres champs si nécessaire, ex : 'slug', 'image'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // protected static function booted()
    // {
    //     static::creating(function ($brand) {
    //         if (empty($brand->slug)) {
    //             $slug = Str::slug($brand->name);
    //             $originalSlug = $slug;
    //             $counter = 1;
    //             while (Brand::where('slug', $slug)->exists()) {
    //                 $slug = $originalSlug . '-' . $counter;
    //                 $counter++;
    //             }
    //             $brand->slug = $slug;
    //         }
    //     });
    // }

}
