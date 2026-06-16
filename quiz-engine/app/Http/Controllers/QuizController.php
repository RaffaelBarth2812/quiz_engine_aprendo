<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

//-------------------------------------------------------------------------------------------------
class QuizController extends Controller
{
    public function store(Request $request)
    {
        // Validierung von Request-Daten, https://laravel.com/docs/13.x/validation
        $validatedRequest = $request->validate([
            'title'                             => ['required', 'string'],
            'questions'                         => ['required', 'array', 'min:1'],
            'questions.*.text'                  => ['required', 'string'],
            'questions.*.answers'               => ['required', 'array', 'min:2'],
            'questions.*.answers.*.text'        => ['required', 'string'],
            'questions.*.answers.*.is_correct'  => ['required', 'boolean'],
        ]);

        $quiz = Quiz::create([
            'title' => $validatedRequest['title'],
        ]);

        // For each question in validatedRequest
        foreach ($validatedRequest['questions'] as $questionData) {
            // Create Question
            $question = Question::create([
                'quiz_id' => $quiz->id,
                'text'    => $questionData['text'],
                'type'    => 'single_choice',
            ]);

            // For each answer in in questionData
            foreach ($questionData['answers'] as $answerData) {
                // Create answer
                Answer::create([
                    'question_id' => $question->id,
                    'text'        => $answerData['text'],
                    'is_correct'  => $answerData['is_correct'],
                ]);
            }
        }

        return response()->json([
            'id'      => $quiz->id,
            'message' => 'Quiz created'
        ], 201);
    }

    //---------------------------------------------------------------------------------------------
    public function show($id)
    {
        $quiz = Quiz::with('questions.answers')->findOrFail($id);

        $questions = [];

        foreach ($quiz->questions as $question) {
            $answers = [];

            foreach ($question->answers as $answer) {
                $answers[] = [
                    'id'   => $answer->id,
                    'text' => $answer->text,
                ];
            }

            $questions[] = [
                'id'      => $question->id,
                'text'    => $question->text,
                'type'    => $question->type,
                'answers' => $answers,
            ];
        }

        return response()->json([
            'id'        => $quiz->id,
            'title'     => $quiz->title,
            'questions' => $questions,
        ]);
    }
}