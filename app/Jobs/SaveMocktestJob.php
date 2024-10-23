<?php

namespace App\Jobs;

use App\Models\Mocktest;
use App\Models\MocktestAnswer;
use App\Models\MocktestUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SaveMocktestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;

    /**
     * Create a new job instance.
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = $this->request;
        $mockUser = MocktestUser::query()
            ->with('user')
            ->where('exam_token', $data['token'])
            ->first();

        $questionsCount = Mocktest::query()
            ->withCount('questions')
            ->where('id', $mockUser->mocktest_id)
            ->pluck('questions_count')
            ->toArray();

        if ($mockUser) {
            $correct = 0;
            foreach ($data['question'] as $question) {
                if ($question) {
                    if (isset($question['userAns']) && $question['answer'] === Str::lower($question['userAns'])) {
                        $mockUser->mark += $question['mark'];
                        $mockUser->total_correct = $mockUser->total_correct + 1;
                        $correct++;
                    } else {
//                        $mockUser->total_incaorrect = (int)$mockUser->total_incaorrect + 1;
                        if (isset($data['minus_mark']) && isset($question['userAns'])) {
                            $mockUser->minus_mark = $mockUser->minus_mark + (($question['mark'] * $data['minus_mark']) / 100);
                        }
                    }
                }
                MocktestAnswer::query()
                    ->where('mocktest_id', $mockUser->mocktest_id)
                    ->where('question_id', $question['id'])
                    ->where('user_id', $mockUser->user_id)
                    ->where('mocktest_user_id', $mockUser->id)
                    ->update(['user_answer' => $question['userAns'] ?? null]);
            }
            $mockUser->total_incaorrect = $questionsCount[0] - $correct;
            $mockUser->show_result = true;
            $mockUser->save();
        } else {
            Log::error('mock user not found');
        }

        Log::info('Result Created successfoll for:',[$mockUser->user_id]);
    }
}
