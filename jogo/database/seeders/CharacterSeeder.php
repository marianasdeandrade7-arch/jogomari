<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CharacterSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        DB::table('characters')->insert([
            [
                'name' => 'Lyra',
                'level' => 5,
                'vida' => 120,
                'poder' => 30,
                'xp' => 150,
                'ataque' => 18,
                'defesa' => 10,
                'image' => 'https://i.pinimg.com/736x/7b/bf/ee/7bbfee1f9ef36762eb90e7805077060c.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Kael',
                'level' => 4,
                'vida' => 110,
                'poder' => 25,
                'xp' => 120,
                'ataque' => 20,
                'defesa' => 12,
                'image' => 'https://i.pinimg.com/736x/3c/fa/36/3cfa36141945e396d9909288205c8755.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Thorne',
                'level' => 6,
                'vida' => 140,
                'poder' => 20,
                'xp' => 200,
                'ataque' => 25,
                'defesa' => 8,
                'image' => 'https://i.pinimg.com/736x/77/6c/a6/776ca6345b8aa6c3683981da61dec649.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
