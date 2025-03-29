<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Salário', 'type' => 'income', 'color' => '#00C853', 'is_system' => 1],
            ['name' => 'Bônus', 'type' => 'income', 'color' => '#2962FF', 'is_system' => 1],
            ['name' => 'Investimentos', 'type' => 'income', 'color' => '#FF6D00', 'is_system' => 1],
            ['name' => 'Outros Rendimentos', 'type' => 'income', 'color' => '#6200EA', 'is_system' => 1],
            ['name' => 'Alimentação', 'type' => 'expense', 'color' => '#F44336','is_system' => 1],
            ['name' => 'Transporte', 'type' => 'expense', 'color' => '#2196F3', 'is_system' => 1],
            ['name' => 'Moradia', 'type' => 'expense', 'color' => '#4CAF50','is_system' => 1],
            ['name' => 'Lazer', 'type' => 'expense', 'color' => '#9C27B0',  'is_system' => 1],
            ['name' => 'Saúde', 'type' => 'expense', 'color' => '#F50057','is_system' => 1],
            ['name' => 'Educação', 'type' => 'expense', 'color' => '#FF9800','is_system' => 1],
            ['name' => 'Compras', 'type' => 'expense', 'color' => '#795548', 'is_system' => 1],
            ['name' => 'Contas', 'type' => 'expense', 'color' => '#607D8B','is_system' => 1],
            ['name' => 'Transferência', 'type' => 'transfer', 'color' => '#BDBDBD','is_system' => 1],
        ]);
    }
}
