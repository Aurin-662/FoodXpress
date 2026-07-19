<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'parent_id'];

    public static function ensureDefaultCategories()
    {
        $defaults = ['Value Meal', 'Drinks', 'Desserts'];
        $categories = collect();

        foreach ($defaults as $name) {
            $category = self::firstOrCreate(['name' => $name], ['parent_id' => null]);
            $categories->push($category);
        }

        return $categories;
    }

    public function foods()
    {
        return $this->hasMany(Food::class);
    }

    // parent category (e.g. Burger -> Value Meal)
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // child categories (e.g. Value Meal -> Burger, Pizza, Pasta, Soup)
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}