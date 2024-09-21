<?php

namespace App\Http\Controllers\Api\Student\V1;


use App\Http\Controllers\Controller;
use App\Models\Mocktest;
use App\Models\MocktestAnswer;
use App\Models\MocktestUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class MocktestController extends Controller
{
    public function todayLiveExam(): \Illuminate\Http\JsonResponse
    {
        $data = DB::table('course_mocktest')
            ->join('mocktests', 'course_mocktest.mocktest_id', '=', 'mocktests.id')
            ->join('courses', 'course_mocktest.course_id', '=', 'courses.id')
            ->select(
                'courses.name as course_name',
                'courses.id as course_id',
                'course_mocktest.*',
                'mocktests.*')
            ->where('start_time', '<=', Carbon::now())
            ->where('end_time', '>=', Carbon::now())
            ->simplePaginate(10);
        return response()->json($data);
    }


    public function myMocktests(): \Illuminate\Http\JsonResponse
    {
        $ids = DB::table('mocktest_user')
            ->distinct()
            ->where('exam_type', '=', 'live')
            ->where('user_id', Auth::id())
            ->pluck('mocktest_id');


        $data = DB::table('course_mocktest')
            ->join('mocktests', 'course_mocktest.mocktest_id', '=', 'mocktests.id')
            ->join('courses', 'course_mocktest.course_id', '=', 'courses.id')
            ->whereNotIn('mocktests.id', $ids)
            ->where('end_time', '>=', Carbon::now())
            ->select(
                'courses.name as course_name',
                'courses.id as course_id',
                'course_mocktest.*',
                'mocktests.*')
            ->simplePaginate(10);

        return response()->json($data);
    }
    public function getGivenLiveMocktests(): \Illuminate\Http\JsonResponse
    {

        $data = DB::table('mocktest_user')
            ->join('mocktests', 'mocktest_user.mocktest_id', '=', 'mocktests.id')
            ->where('mocktest_user.user_id', '=', Auth::id())
            ->where('mocktest_user.exam_type', '=', 'live')
            ->select(['mocktest_user.id as mock_user_id', 'mocktests.name', 'mocktests.total_q', 'mocktests.duration'])
            ->simplePaginate(10);
        return response()->json($data);
    }


    public function getPracticeExams(): \Illuminate\Http\JsonResponse
    {
        $data = DB::table('course_mocktest')
            ->join('mocktests', 'course_mocktest.mocktest_id', '=', 'mocktests.id')
            ->join('courses', 'course_mocktest.course_id', '=', 'courses.id')
            ->select(
                'courses.name as course_name',
                'courses.id as course_id',
                'course_mocktest.*',
                'mocktests.*')
            ->where('end_time', '<=', Carbon::now())
            ->simplePaginate(10);


        return response()->json($data);
    }

    public function getMocktestDetails($id) //: \Illuminate\Http\JsonResponse
    {
        $mocktest = Mocktest::query()
            ->findOrFail($id)
            ->makeHidden('questions');
        return response()->json($mocktest);
    }


    public function checkMocktest(Request $request, $id)
    {
        DB::beginTransaction();
        $mock = Mocktest::query()
            ->findOrFail($id);

        $existGiven = DB::table('mocktest_user')
            ->where('user_id', Auth::id())
            ->where('mocktest_id', $mock->id)
            ->where('exam_type', '=', 'live')
            ->first();

        if($mock->end_time < now() || !empty($existGiven)){
            return response()->json([
                'message' => 'Mocktest Not Valid'
            ], 404);
        }

        $examToken = Str::random(10);
        $arr= [
            'mocktest_id' => $mock->id,
            'user_id' => Auth::id(),
            'exam_token' => $examToken,
            'exam_type' => 'live'
        ];
        $item = MocktestUser::create($arr);

        Session::push('mock_token', $examToken);


        $mock->questions()->detach();
        $ids = json_decode($mock->questions);

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
        }

        DB::commit();
        if ($mock) {
            return \response()->json([
                'examToken' => $examToken
            ]);
        } else {
            DB::rollBack();
            return response()->json([
                'message' => 'Mocktest Not Valid'
            ], 404);
        }
    }

    public function mockQuestions($id): array
    {
        $mock = Mocktest::query()->with('users')
            ->select(['id', 'duration', 'total_q'])->findOrFail($id); //->wherePassword($request->input('password'))
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
            'data' => $mock->questions()->where('user_id', Auth::id())
                ->select('questions.id', 'questions.title')
                ->paginate(1),
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
        DB::table('mocktest_questions')
            ->where('mocktest_id', $mock->id)
            ->where('user_id', Auth::id())
            ->delete();
        return response()->json(['Mocktest given done...']);
    }

}

