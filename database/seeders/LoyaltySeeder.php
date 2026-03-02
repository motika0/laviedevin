<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Loyalty;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LoyaltySeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Loyalty::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $users = User::all();
        
        foreach ($users as $index => $user) {
            $points = [500, 1200, 0][$index] ?? 100;
            $totalEarned = [1500, 3000, 0][$index] ?? 500;
            $level = $points >= 1000 ? 'золото' : ($points >= 500 ? 'серебро' : 'бронза');
            
            Loyalty::create([
                'user_id' => $user->id,
                'points' => $points,
                'total_earned' => $totalEarned,
                'level' => $level,
            ]);
        }
    }
}