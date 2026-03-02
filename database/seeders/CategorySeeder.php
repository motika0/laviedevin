<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Отключаем проверку внешних ключей
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $categories = [
            [
                'name' => 'Вино',
                'description' => 'Натуральные виноградные вина со всего мира',
                'parent_id' => null,
            ],
            [
                'name' => 'Красное вино',
                'description' => 'Красные вина: от легких до насыщенных',
                'parent_id' => 1, 
            ],
            [
                'name' => 'Белое вино',
                'description' => 'Белые вина: свежие и ароматные',
                'parent_id' => 1,
            ],
            [
                'name' => 'Игристое вино',
                'description' => 'Шампанское и игристые вина',
                'parent_id' => null,
            ],
            [
                'name' => 'Крепкий алкоголь',
                'description' => 'Виски, коньяк, водка и другие крепкие напитки',
                'parent_id' => null,
            ],
            [
                'name' => 'Виски',
                'description' => 'Односолодовый и купажированный виски',
                'parent_id' => 5,
            ],
            [
                'name' => 'Коньяк',
                'description' => 'Французский коньяк и бренди',
                'parent_id' => 5,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}