<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
