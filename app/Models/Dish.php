<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image_url', 'price'];

    public static function rules()
    {
        return [
            'name' => 'required|unique:dishes',
            'description' => 'required|unique:dishes',
            'image_url' => 'required|url',
            'price' => 'required|numeric',
        ];
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(DishRating::class);
    }
}
