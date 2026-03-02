<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Order::truncate();
        OrderItem::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $users = User::all();
        $products = Product::all();

        $order1 = Order::create([
            'user_id' => $users[0]->id,
            'order_number' => 'ORD-2024-0001',
            'total_amount' => 5990.00,
            'discount_amount' => 500.00,
            'final_amount' => 5490.00,
            'bonus_used' => 500,
            'status' => 'выполнен',
            'payment_status' => 'оплачен',
            'address' => 'г. Москва, ул. Ленина, д. 1, кв. 1',
            'phone' => '+7(999)123-45-67',
            'comment' => 'Позвонить заранее',
        ]);

        OrderItem::create([
            'order_id' => $order1->id,
            'product_id' => $products[0]->id,
            'quantity' => 1,
            'price' => 5990.00,
        ]);


        $order2 = Order::create([
            'user_id' => $users[0]->id,
            'order_number' => 'ORD-2024-0002',
            'total_amount' => 5780.00,
            'discount_amount' => 0,
            'final_amount' => 5780.00,
            'bonus_used' => 0,
            'status' => 'выполнен',
            'payment_status' => 'оплачен',
            'address' => 'г. Москва, ул. Ленина, д. 1, кв. 1',
            'phone' => '+7(999)123-45-67',
            'comment' => null,
        ]);

        OrderItem::create([
            'order_id' => $order2->id,
            'product_id' => $products[4]->id,
            'quantity' => 2,
            'price' => 2890.00,
        ]);

        $order3 = Order::create([
            'user_id' => $users[1]->id,
            'order_number' => 'ORD-2024-0003',
            'total_amount' => 2340.00,
            'discount_amount' => 200.00,
            'final_amount' => 2140.00,
            'bonus_used' => 200,
            'status' => 'в обработке',
            'payment_status' => 'ожидает',
            'address' => 'г. Санкт-Петербург, ул. Невский, д. 10, кв. 5',
            'phone' => '+7(999)765-43-21',
            'comment' => 'Оставить у двери',
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $products[2]->id,
            'quantity' => 2,
            'price' => 890.00,
        ]);

        OrderItem::create([
            'order_id' => $order3->id,
            'product_id' => $products[3]->id,
            'quantity' => 1,
            'price' => 1450.00,
        ]);
    }
}