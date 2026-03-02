<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'surname' => 'Украинский',
                'name' => 'Матвей',
                'patronymic' => 'Леонидович',
                'email' => 'ukrmatleo@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+375 (33) 659 72 34',
                'birth_date' => '2005-11-28',
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
            [
                'surname' => 'Ромбальская',
                'name' => 'Яна',
                'patronymic' => 'Сергеевна',
                'email' => 'rombic_opasnaya@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+375 (33) 340 90 73',
                'birth_date' => '2006-09-03',
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
            [
                'surname' => 'Раткевич',
                'name' => 'Елена',
                'patronymic' => 'Сергеевна',
                'email' => 'lenka_macarenka@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+375 29 820 26 00',
                'birth_date' => '2006-02-02',
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
            [
                'surname' => 'Голубцов',
                'name' => 'Алексей',
                'patronymic' => 'Сергеевич',
                'email' => 'leha_diplomat@example.com',
                'password' => Hash::make('password123'),
                'phone' => '+375 44 590 71 43',
                'birth_date' => '2006-06-16',
                'is_verified' => true,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
