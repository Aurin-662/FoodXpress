<?php

namespace Tests\Unit;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_categories_are_created(): void
    {
        Category::query()->delete();

        $categories = Category::ensureDefaultCategories();

        $this->assertCount(3, $categories);
        $this->assertTrue($categories->contains('name', 'Value Meal'));
        $this->assertTrue($categories->contains('name', 'Drinks'));
        $this->assertTrue($categories->contains('name', 'Desserts'));
    }
}
