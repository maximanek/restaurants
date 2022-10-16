<?php

namespace Database\Seeders;

use App\Models\Note;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Note::create([
          'description' => '1st note',
          'user_id' => 1
        ]);

        Note::create([
          'description' => '1st note',
          'restaurant_id' => 1
        ]);
    }
}
