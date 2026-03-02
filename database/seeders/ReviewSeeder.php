<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Review::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $users = User::all();
        $products = Product::all();

        $reviews = [
            [
                'user_id' => $users[0]->id,
                'product_id' => $products[0]->id,
                'rating' => 5,
                'comment' => 'Великолепное вино! Очень насыщенный вкус, долгое послевкусие. Рекомендую!',
                'is_approved' => true,
            ],
            [
                'user_id' => $users[0]->id,
                'product_id' => $products[4]->id,
                'rating' => 5,
                'comment' => 'Лучший виски для вечера в компании. Мягкий, с приятными нотками ванили.',
                'is_approved' => true,
            ],
            [
                'user_id' => $users[1]->id,
                'product_id' => $products[2]->id,
                'rating' => 4,
                'comment' => 'Хорошее вино за свою цену. Легкое, приятное, подходит для ужина.',
                'is_approved' => true,
            ],
            [
                'user_id' => $users[1]->id,
                'product_id' => $products[6]->id,
                'rating' => 5,
                'comment' => 'Отличный коньяк! Настоящий французский вкус.',
                'is_approved' => true,
            ],
            [
                'user_id' => $users[2]->id,
                'product_id' => $products[1]->id,
                'rating' => 3,
                'comment' => 'Неплохое вино, но ожидал большего. Цена немного завышена.',
                'is_approved' => false,
            ],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }
    }
}