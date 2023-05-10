<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Question::factory(5)->create()->each(function ($question) {
            Answer::factory()->create([
                'question_id' => $question->id,
                'name' => 'Sí',
            ]);

            Answer::factory()->create([
                'question_id' => $question->id,
                'name' => 'No',
            ]);
        });
    }
}
