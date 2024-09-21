<?php

namespace App\Http\Controllers\Api\Student\V1;


use App\Http\Controllers\Controller;
use App\Models\GivenMocktestAnswer;
use App\Models\Mocktest;
use App\Models\MocktestAnswer;
use App\Models\MocktestUser;
use App\Models\Order;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class PracticeMocktestController extends Controller
{

    public function getPracticeExams() //: array|\Illuminate\Database\Eloquent\Collection
    {
        $order = Order::query()->where('user_id', Auth::id())->distinct()->pluck('course_id')->toArray();
        $data = DB::table('course_mocktest')
            ->join('mocktests', 'course_mocktest.mocktest_id', '=', 'mocktests.id')
            ->join('courses', 'course_mocktest.course_id', '=', 'courses.id')
            ->whereIn('courses.id', $order)
            ->where('end_time', '<=', Carbon::now())
            ->select(
                'courses.name as course_name',
                'courses.id as course_id',
                'course_mocktest.*',
                'mocktests.*')
            ->simplePaginate(10);


        return response()->json($data);
    }

    public function getMocktestDetails($id): \Illuminate\Http\JsonResponse
    {
        $mocktest = Mocktest::query()
            ->findOrFail($id)
            ->makeHidden('questions');
        return response()->json($mocktest);
    }

    public function checkMocktest(Request $request, $id) //: \Illuminate\Http\JsonResponse|array
    {
        $mock = Mocktest::query()->select('id', 'questions as question_ids')->findOrFail($id);

        if ($mock->end_time > now()) {
            return \response()->json(["This is now live exam. try after finished live exam"], 500);
        }

        $examToken = Str::random(10);
        $arr= [
            'mocktest_id' => $mock->id,
            'user_id' => Auth::id(),
            'exam_token' => $examToken,
            'exam_type' => 'practice'
        ];
        $item = MocktestUser::create($arr);

//        $mock->users()->attach($arr);
        Session::push('mock_token', $examToken);


        $mock->questions()->detach();
        $ids = json_decode($mock->question_ids);
        if ($mock && count($ids)) {
            $mockQuestions = [];
            $mockAns = [];
            foreach ($ids as $question) {
                $mockQuestions[] = [
                    'mocktest_id' => $mock->id,
                    'question_id' => $question,
                    'user_id' => Auth::id()
                ];
                $mockAns[] = [
                    'mocktest_id' => $mock->id,
                    'mocktest_user_id' => $item->id,
                    'question_id' => $question,
                    'user_id' => Auth::id()
                ];
            }
            $mock->questions()->attach($mockQuestions);
            MocktestAnswer::query()->insert($mockAns);
//            $mock->givenAnswers()->createMany($mockAns);
        }

        $arr = [];
        if ($mock) {
            return \response()->json([
                'examToken' => $examToken
            ]);
        } else {
            return response()->json([
                'message' => 'Mocktest Not Valid'
            ], 404);
        }
    }

    public function mockQuestions($id)
    {
        $mock = Mocktest::query()->with('users')->select(['id', 'duration', 'total_q'])->findOrFail($id); //->wherePassword($request->input('password'))
        $mock->isShowQustions = true;


        if (\request()->has('mockId') && request()->has('token') && $mock) {
            $mockUser = DB::table('mocktest_user')
                ->where('exam_token', \request()->input('token'))
                ->pluck('id')
                ->first();

            DB::table('mocktest_answer')
                ->where('mocktest_id', request()->input('mockId'))
                ->where('user_id', Auth::id())
                ->where('mocktest_user_id', $mockUser)
                ->where('question_id', request()->input('qId'))
                ->update([
                    'user_answer' => \request()->input('qAns'),
                ]);
        }

        return [
            'data' => $mock->questions()->where('user_id', Auth::id())->select('questions.id', 'questions.title')->paginate(1),
            'mock' => $mock,
            'answer_details' => request()->has('mockId') ? \request()->all() : 'no have answer details'
        ];
    }


    public function finishMocktest(Request $request): \Illuminate\Http\JsonResponse
    {
        $mock = Mocktest::query()->with('users')->findOrFail($request->input('mockId'));
        if (\request()->has('mockId') && request()->has('token') && $mock) {
            $mockUser = DB::table('mocktest_user')
                ->where('exam_token', \request()->input('token'))
                ->pluck('id')
                ->first();

            DB::table('mocktest_answer')
                ->where('mocktest_id', request()->input('mockId'))
                ->where('user_id', Auth::id())
                ->where('mocktest_user_id', $mockUser)
                ->where('question_id', request()->input('qId'))
                ->update([
                    'user_answer' => \request()->input('qAns'),
                ]);
        }
        DB::table('mocktest_questions')->where('mocktest_id', $mock->id)->where('user_id', Auth::id())->delete();
        return response()->json(['Mocktest given done...']);
    }


    public function showResults($id): \Illuminate\Http\JsonResponse
    {
        $results = MocktestUser::query()
            ->with(['mocktest'])
            ->where('mocktest_id', $id)
            ->where('user_id', Auth::id())
            ->simplePaginate(10);

        return response()->json($results);
    }

    public function showResultDetails($id)
    {
        $answers = MocktestAnswer::query()
            ->with('question')
            ->where('mocktest_user_id', $id)
            ->where('user_id', Auth::id())
            ->get();

        $marks = 0;
        $correct = 0;
        $incorrect = 0;

        foreach ($answers as $item) {
            if ($item["user_answer"] == Str::lower($item->question->answer)) {
                $marks += $item->question->mark;
                $correct++;
            } else {
                $incorrect++;
            }
        }

        $givenAnsweres = [
            'marks' => $marks,
            'correct' => $correct,
            'incorrect' => $incorrect,
            'totalAnswered' => count($answers)
        ];

        return \response()->json([
//            'examDetails' => $resultDetails,
            'ansCountes' => $givenAnsweres,
            'answerDetails' => $answers
        ], 200);

    }
}

