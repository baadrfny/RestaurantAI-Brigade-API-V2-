<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Plat;
use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@restaurantai.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'dietary_tags' => [],
        ]);

        // Create test user
        User::create([
            'name' => 'Test User',
            'email' => 'user@restaurantai.com',
            'password' => bcrypt('password'),
            'role' => 'client',
            'dietary_tags' => ['vegetarian', 'gluten-free'],
        ]);

        // Create categories
        $categories = [
            ['name' => 'Appetizers'],
            ['name' => 'Main Courses'],
            ['name' => 'Desserts'],
            ['name' => 'Beverages'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create ingredients
        $ingredients = [
            ['name' => 'Chicken', 'tags' => ['protein', 'meat', 'animal']],
            ['name' => 'Beef', 'tags' => ['protein', 'meat', 'animal']],
            ['name' => 'Salmon', 'tags' => ['protein', 'fish', 'animal']],
            ['name' => 'Rice', 'tags' => ['gluten', 'carb', 'grain']],
            ['name' => 'Pasta', 'tags' => ['gluten', 'carb', 'grain']],
            ['name' => 'Tomatoes', 'tags' => ['vegetable', 'plant']],
            ['name' => 'Cheese', 'tags' => ['dairy', 'animal']],
            ['name' => 'Lettuce', 'tags' => ['vegetable', 'plant']],
            ['name' => 'Olive Oil', 'tags' => ['fat', 'plant']],
            ['name' => 'Garlic', 'tags' => ['vegetable', 'plant']],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }

        // Create sample plates
        $appetizers = Category::where('name', 'Appetizers')->first();
        $mainCourses = Category::where('name', 'Main Courses')->first();
        $desserts = Category::where('name', 'Desserts')->first();

        $plates = [
            [
                'name' => 'Caesar Salad',
                'description' => 'Fresh romaine lettuce with parmesan cheese and croutons',
                'price' => 8.99,
                'category_id' => $appetizers->id,
            ],
            [
                'name' => 'Grilled Chicken',
                'description' => 'Tender grilled chicken breast with herbs',
                'price' => 18.99,
                'category_id' => $mainCourses->id,
            ],
            [
                'name' => 'Salmon Pasta',
                'description' => 'Fresh salmon served over pasta with tomato sauce',
                'price' => 22.99,
                'category_id' => $mainCourses->id,
            ],
            [
                'name' => 'Chocolate Cake',
                'description' => 'Rich chocolate cake with vanilla frosting',
                'price' => 6.99,
                'category_id' => $desserts->id,
            ],
        ];

        foreach ($plates as $plate) {
            $createdPlate = Plat::create($plate);
            
            // Add ingredients to plates
            if ($createdPlate->name === 'Caesar Salad') {
                $createdPlate->ingredients()->attach([1, 7, 8, 9, 10]); // Lettuce, Tomatoes, Cheese, Olive Oil, Garlic
            } elseif ($createdPlate->name === 'Grilled Chicken') {
                $createdPlate->ingredients()->attach([1]); // Chicken
            } elseif ($createdPlate->name === 'Salmon Pasta') {
                $createdPlate->ingredients()->attach([3, 5, 6, 10]); // Salmon, Pasta, Tomatoes, Olive Oil
            } elseif ($createdPlate->name === 'Chocolate Cake') {
                $createdPlate->ingredients()->attach([6, 10]); // Cheese, Olive Oil (sugar not in ingredients but cake implies sugar)
            }
        }
    }
}
