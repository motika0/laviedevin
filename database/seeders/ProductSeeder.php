<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $products = [
            [
                'name' => 'Шато Марго 2015',
                'description' => 'Изысканное красное вино из региона Бордо. Обладает глубоким рубиновым цветом и сложным ароматом с нотами черных ягод, трюфелей и дуба.',
                'price' => 59.00,
                'old_price' => 69.00,
                'volume' => 0.75,
                'alcohol' => 13.5,
                'country' => 'Франция',
                'stock' => 10,
                'image' => 'images/margo.png',
                'category_id' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Кьянти Классико',
                'description' => 'Итальянское красное вино из Тосканы. Свежее, с нотами вишни и фиалки.',
                'price' => 189.00,
                'old_price' => null,
                'volume' => 0.75,
                'alcohol' => 12.5,
                'country' => 'Италия',
                'stock' => 25,
                'image' => 'images/classiko.jfif',
                'category_id' => 2,
                'is_active' => true,
            ],

            [
                'name' => 'Шардоне "Золотая балка"',
                'description' => 'Белое сухое вино из Крыма. Легкое, с нотами яблок и цитрусовых.',
                'price' => 89.00,
                'old_price' => 99.00,
                'volume' => 0.75,
                'alcohol' => 12.0,
                'country' => 'Россия',
                'stock' => 50,
                'image' => 'images/shardon.jpg',
                'category_id' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Рислинг "Тропикана"',
                'description' => 'Немецкий рислинг с тропическими нотами и приятной кислинкой.',
                'price' => 145.00,
                'old_price' => null,
                'volume' => 0.75,
                'alcohol' => 11.5,
                'country' => 'Германия',
                'stock' => 15,
                'image' => 'images/riesling.jfif',
                'category_id' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Моет э Шандон Империал',
                'description' => 'Легендарное шампанское с нотами груши, цитрусовых и бриоши.',
                'price' => 69.00,
                'old_price' => 79.00,
                'volume' => 0.75,
                'alcohol' => 12.0,
                'country' => 'Франция',
                'stock' => 5,
                'image' => 'images/margo.png',
                'category_id' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Джек Дэниэлс',
                'description' => 'Американский виски с характерным вкусом ванили и дуба.',
                'price' => 29.00,
                'old_price' => 29.00,
                'volume' => 0.7,
                'alcohol' => 40.0,
                'country' => 'США',
                'stock' => 15,
                'image' => 'images/shardon.jpg',
                'category_id' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Джонни Уокер Блэк Лейбл',
                'description' => 'Купажированный шотландский виски с дымными нотами.',
                'price' => 59.00,
                'old_price' => null,
                'volume' => 0.7,
                'alcohol' => 40.0,
                'country' => 'Шотландия',
                'stock' => 8,
                'image' => 'images/margo.png',
                'category_id' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Хеннесси VS',
                'description' => 'Французский коньяк с нотами ванили и специй.',
                'price' => 45.00,
                'old_price' => 49.00,
                'volume' => 0.5,
                'alcohol' => 40.0,
                'country' => 'Франция',
                'stock' => 8,
                'image' => 'images/margo.png',
                'category_id' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
        
    }
    
}