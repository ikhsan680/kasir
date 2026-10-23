<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashierQuizController extends Controller
{
    public function home()
    {
        return view('cashier.home');
    }

    public function start(Request $request)
    {
        $range = $request->input('range', 'below_100');
        $mode = $request->input('mode', 'practice');
        $questionCount = $this->resolveQuestionCount($request);

        $questions = $this->buildQuestions($range, $questionCount);

        session([
            'cashier_questions' => $questions,
            'cashier_index' => 0,
            'cashier_answers' => [],
            'cashier_mode' => $mode,
            'cashier_range' => $range,
            'cashier_feedback' => null,
            'cashier_error' => null,
            'cashier_question_times' => [],
            'cashier_question_started_at' => time(),
        ]);

        return redirect()->route('cashier.quiz');
    }

    public function quiz()
    {
        $questions = session('cashier_questions');
        $index = session('cashier_index', 0);
        $mode = session('cashier_mode', 'practice');

        if (empty($questions)) {
            return redirect()->route('cashier.home');
        }

        if (!isset($questions[$index])) {
            return redirect()->route('cashier.result');
        }

        $total = count($questions);
        $current = $questions[$index];
        $feedback = session('cashier_feedback');
        $error = session('cashier_error');

        return view('cashier.quiz', [
            'index' => $index,
            'total' => $total,
            'current' => $current,
            'mode' => $mode,
            'feedback' => $feedback,
            'error' => $error,
        ]);
    }

    public function nextQuestion()
    {
        $questions = session('cashier_questions', []);
        $index = session('cashier_index', 0);

        if (empty($questions)) {
            return redirect()->route('cashier.home');
        }

        session(['cashier_feedback' => null, 'cashier_error' => null]);

        if ($index + 1 >= count($questions)) {
            return redirect()->route('cashier.result');
        }

        session([
            'cashier_index' => $index + 1,
            'cashier_question_started_at' => time(),
        ]);

        return redirect()->route('cashier.quiz');
    }

    public function submit(Request $request)
    {
        $questions = session('cashier_questions');
        $index = session('cashier_index', 0);
        $mode = session('cashier_mode', 'practice');

        if (empty($questions) || !isset($questions[$index])) {
            return redirect()->route('cashier.home');
        }

        $current = $questions[$index];
        $rawAnswer = $request->input('answer');

        if ($rawAnswer === null || trim((string) $rawAnswer) === '') {
            return redirect()->route('cashier.quiz')->with('cashier_error', 'Jawab terlebih dahulu sebelum lanjut ke soal berikutnya.');
        }

        $answer = (int) $rawAnswer;
        $isCorrect = $answer === $current['change'];
        $answers = session('cashier_answers', []);
        $elapsedSeconds = max(0, (int) $request->input('elapsed_seconds', 0));
        $questionTimes = session('cashier_question_times', []);
        $questionTimes[$index] = $elapsedSeconds;

        $answers[$index] = [
            'question' => $current,
            'answer' => $answer,
            'correct_answer' => $current['change'],
            'is_correct' => $isCorrect,
        ];

        session([
            'cashier_answers' => $answers,
            'cashier_error' => null,
            'cashier_question_times' => $questionTimes,
            'cashier_question_started_at' => time(),
        ]);

        if ($mode === 'practice') {
            session([
                'cashier_feedback' => [
                    'index' => $index,
                    'answer' => $answer,
                    'correct_answer' => $current['change'],
                    'is_correct' => $isCorrect,
                ],
            ]);

            return redirect()->route('cashier.quiz');
        }

        if ($index + 1 >= count($questions)) {
            return redirect()->route('cashier.result');
        }

        session([
            'cashier_index' => $index + 1,
            'cashier_feedback' => null,
            'cashier_question_started_at' => time(),
        ]);

        return redirect()->route('cashier.quiz');
    }

    public function result()
    {
        $questions = session('cashier_questions', []);
        $answers = session('cashier_answers', []);
        $mode = session('cashier_mode', 'practice');
        $questionTimes = session('cashier_question_times', []);

        if (empty($questions)) {
            return redirect()->route('cashier.home');
        }

        $score = 0;
        foreach ($answers as $item) {
            if (($item['is_correct'] ?? false) === true) {
                $score++;
            }
        }

        $summary = [];
        foreach ($questions as $index => $question) {
            $summary[] = [
                'number' => $index + 1,
                'price' => $question['price'],
                'payment' => $question['payment'],
                'change' => $question['change'],
                'answer' => $answers[$index]['answer'] ?? 0,
                'correct_answer' => $answers[$index]['correct_answer'] ?? $question['change'],
                'is_correct' => $answers[$index]['is_correct'] ?? false,
                'answered' => array_key_exists($index, $answers),
                'time_taken' => $questionTimes[$index] ?? 0,
            ];
        }

        return view('cashier.result', [
            'score' => $score,
            'total' => count($questions),
            'summary' => $summary,
            'mode' => $mode,
        ]);
    }

    public function finishExam(Request $request)
    {
        $questions = session('cashier_questions', []);
        $answers = session('cashier_answers', []);
        $index = session('cashier_index', 0);
        $elapsedSeconds = max(0, (int) $request->input('elapsed_seconds', 0));
        $questionTimes = session('cashier_question_times', []);

        if (!empty($questions) && isset($questions[$index]) && !isset($answers[$index])) {
            $questionTimes[$index] = $elapsedSeconds;
        }

        session([
            'cashier_question_times' => $questionTimes,
            'cashier_question_started_at' => time(),
        ]);

        return redirect()->route('cashier.result');
    }

    public function reset()
    {
        session()->forget(['cashier_questions', 'cashier_index', 'cashier_answers', 'cashier_mode', 'cashier_range', 'cashier_feedback', 'cashier_error', 'cashier_question_times', 'cashier_question_started_at']);

        return redirect()->route('cashier.home');
    }

    public function restartSameQuiz()
    {
        $range = session('cashier_range', 'below_100');
        $mode = session('cashier_mode', 'practice');
        $questionCount = count(session('cashier_questions', [])) ?: 10;

        $questions = $this->buildQuestions($range, $questionCount);

        session([
            'cashier_questions' => $questions,
            'cashier_index' => 0,
            'cashier_answers' => [],
            'cashier_mode' => $mode,
            'cashier_range' => $range,
            'cashier_feedback' => null,
            'cashier_error' => null,
            'cashier_question_times' => [],
            'cashier_question_started_at' => time(),
        ]);

        return redirect()->route('cashier.quiz');
    }

    public static function formatMoney(int $value): string
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }

    private function buildQuestions(string $range, int $questionCount = 10): array
    {
        $questions = [];

        for ($i = 1; $i <= $questionCount; $i++) {
            $price = $this->generatePrice($range);
            $payment = $this->generatePayment($price, $range);
            $change = $payment - $price;

            $questions[] = [
                'number' => $i,
                'price' => $price,
                'payment' => $payment,
                'change' => $change,
            ];
        }

        return $questions;
    }

    private function resolveQuestionCount(Request $request): int
    {
        $customValue = (int) $request->input('custom_question_count', 0);

        if ($customValue > 0) {
            return min(max($customValue, 1), 200);
        }

        $selected = $request->input('question_count', '10');
        $numericSelected = (int) $selected;

        if ($numericSelected > 0) {
            return min(max($numericSelected, 1), 200);
        }

        return 10;
    }

    private function generatePrice(string $range): int
    {
        if ($range === 'above_100') {
            $min = 110000;
            $max = 500000;
            $value = random_int((int) ($min / 1000), (int) ($max / 1000)) * 1000;
            return $this->roundToThousand($value);
        }

        $value = random_int(5000, 99000);
        return $this->roundToThousand($value);
    }

    private function generatePayment(int $price, string $range): int
    {
        $candidates = $range === 'above_100'
            ? [100000, 120000, 150000, 200000, 250000, 300000, 350000, 400000, 500000]
            : [500, 1000, 2000, 5000, 10000, 20000, 50000, 100000];

        $valid = array_values(array_filter($candidates, fn ($value) => $value > $price));

        if (!empty($valid)) {
            return $valid[array_rand($valid)];
        }

        $extra = $range === 'above_100' ? random_int(1000, 50000) : random_int(500, 20000);
        return $this->roundToThousand($price + $extra);
    }

    private function roundToThousand(int $value): int
    {
        return (int) round($value / 1000) * 1000;
    }
}
