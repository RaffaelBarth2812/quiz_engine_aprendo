<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Quiz;
use App\Models\QuizSession;
use App\Models\SessionAnswer;

//-------------------------------------------------------------------------------------------------
class QuizSessionController extends Controller
{
    public function start($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);

        $session = QuizSession::create([
            'quiz_id' => $quiz->id,
            'started_at' => now(),
        ]);

        return response()->json([
            'session_id' => $session->id,
            'quiz_id' => $quiz->id,
            'started_at' => $session->started_at,
        ], 201);
    }

    //---------------------------------------------------------------------------------------------
    public function finish($sessionId)
    {
        $session = QuizSession::findOrFail($sessionId);
        $session->finished_at = now();
        $session->save();

        return response()->json([
            'message' => 'Session finished'
        ]);
    }

    //---------------------------------------------------------------------------------------------
    public function submitAnswers(Request $request, $sessionId)
    {
        $validated = $request->validate([
            'answers'               => 'required|array|min:1',
            'answers.*.question_id' => 'required|integer|exists:questions,id',
            'answers.*.answer_id'   => 'required|integer|exists:answers,id',
        ]);

        $session = QuizSession::findOrFail($sessionId);

        foreach ($validated['answers'] as $answerData) {
            // Create wenn: Noch keine Antwort für Frage vorhanden
            // Update wenn: Antwort bereits vorhanden und wird von User revidiert
            SessionAnswer::updateOrCreate(
                [
                    'session_id' => $session->id,
                    'question_id' => $answerData['question_id'],
                ],
                [
                    'answer_id' => $answerData['answer_id'],
                ]
            );
        }

        return response()->json([
            'message' => 'Answers submitted',
        ]);
    }

    //---------------------------------------------------------------------------------------------
    public function results($sessionId)
    {
        $session = QuizSession::with([
            'quiz.questions.answers',
            'answers.answer',
        ])->findOrFail($sessionId);

        $score = 0;
        $maxScore = $session->quiz->questions->count();
        $details = [];

        foreach ($session->quiz->questions as $question) {
            $submittedAnswer = $session->answers->firstWhere('question_id', $question->id);

            if ($submittedAnswer === null || $submittedAnswer->answer === null)
                continue;
            
            $isCorrect = $submittedAnswer->answer->is_correct;

            if ($isCorrect)
                $score++;

            $details[] = [
                'question_id' => $question->id,
                'correct' => $isCorrect,
            ];
        }

        return response()->json([
            'score' => $score,
            'max_score' => $maxScore,
            'details' => $details,
        ]);
    }
}
