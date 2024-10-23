<?php

namespace App\Http\Controllers\Api\Student\V1;


use App\Http\Controllers\Controller;
use App\Jobs\SaveMocktestJob;
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
        $arr = [
            'mocktest_id' => $mock->id,
            'user_id' => Auth::id(),
            'exam_token' => $examToken,
            'exam_type' => 'practice'
        ];

        $item = MocktestUser::query()
            ->where('mocktest_id', $arr['mocktest_id'])
            ->where('user_id', $arr['user_id'])
            ->where('exam_type', $arr['exam_type'])
            ->first();
        if ($item) {
            DB::table('mocktest_answer')
                ->where('mocktest_user_id', $item->id)
                ->where('mocktest_id', $mock->id)
                ->where('user_id', Auth::id())->delete();
            $item->delete();
        }

        $item = MocktestUser::create($arr);
        Session::push('mock_token', $examToken);

        $ids = json_decode($mock->question_ids);
        if ($mock && count($ids)) {
            $mockQuestions = [];
            $mockAns = [];
            foreach ($ids as $question) {
//                $mockQuestions[] = [
//                    'mocktest_id' => $mock->id,
//                    'question_id' => $question,
//                    'user_id' => Auth::id()
//                ];
                $mockAns[] = [
                    'mocktest_id' => $mock->id,
                    'mocktest_user_id' => $item->id,
                    'question_id' => $question,
                    'user_id' => Auth::id()
                ];

            }
            MocktestAnswer::query()->insert($mockAns);
//            $mock->questions()->attach($mockQuestions);
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
        $mock = Mocktest::query()->with('users')
            ->select(['id', 'duration', 'total_q', 'minus_mark'])->findOrFail($id); //->wherePassword($request->input('password'))
        $mock->isShowQustions = true;

        if (\request()->has('mockId') && request()->has('token') && $mock) {
            $mockUser = MocktestUser::query()   //DB::table('mocktest_user')
            ->where('exam_token', \request()->input('token'))
                ->first();

            $temp = Question::query()->find(request()->input('qId'));
            if ($temp) {
                if ($temp->answer === Str::lower(request()->input('qAns'))) {
                    $mockUser->mark += $temp->mark;
                    $mockUser->total_correct = $mockUser->correct + 1;
                } else {
                    $mockUser->total_incaorrect = (int)$mockUser->total_incaorrect + 1;
                    if (!empty($mock->minus_mark) && request()->input('qAns')) {
                        $mockUser->minus_mark = $mockUser->minus_mark + (($temp->mark * $mock->minus_mark) / 100);
                    }
                }
                $mockUser->save();
            }

            DB::table('mocktest_answer')
                ->where('mocktest_id', request()->input('mockId'))
                ->where('user_id', Auth::id())
                ->where('mocktest_user_id', $mockUser->id)
                ->where('question_id', request()->input('qId'))
                ->update([
                    'user_answer' => \request()->input('qAns'),
                ]);
        }

        return [
            'data' => $mock->questions()->where('user_id', Auth::id())->select('questions.id', 'questions.title')->paginate(12),
            'mock' => $mock,
            'answer_details' => request()->has('mockId') ? \request()->all() : 'no have answer details'
        ];
    }


    public function finishMocktest(Request $request): \Illuminate\Http\JsonResponse
    {
        $mock = Mocktest::query()->with('users')
            ->select(['id','minus_mark'])
            ->findOrFail($request->input('mockId'))
            ->first();

        $data = $request->all();
        $data['minus_mark'] = $mock->minus_mark;
        SaveMocktestJob::dispatch($data);
        return response()->json('Exam given successfully done...');

/*        $mock = Mocktest::query()->with('users')
            ->select(['id', 'duration', 'total_q', 'minus_mark'])
            ->findOrFail($request->input('mockId'));

        if (\request()->has('mockId') && request()->has('token') && $mock) {
            $mockUser = MocktestUser::query()   //DB::table('mocktest_user')
            ->where('exam_token', \request()->input('token'))
                ->first();

            $temp = Question::query()->find(request()->input('qId'));
            if ($temp) {
                if ($temp->answer === Str::lower(request()->input('qAns'))) {
                    $mockUser->mark += $temp->mark;
                    $mockUser->total_correct = $mockUser->total_correct + 1;
                } else {
                    $mockUser->total_incaorrect = (int)$mockUser->total_incaorrect + 1;
                    if (!empty($mock->minus_mark) && request()->input('qAns')) {
                        $mockUser->minus_mark = $mockUser->minus_mark + (($temp->mark * $mock->minus_mark) / 100);
                    }
                }
                $mockUser->save();
            }

            DB::table('mocktest_answer')
                ->where('mocktest_id', request()->input('mockId'))
                ->where('user_id', Auth::id())
                ->where('mocktest_user_id', $mockUser->id)
                ->where('question_id', request()->input('qId'))
                ->update([
                    'user_answer' => \request()->input('qAns'),
                ]);
        }*/

//        DB::table('mocktest_questions')->where('mocktest_id', $mock->id)->where('user_id', Auth::id())->delete();
//        return response()->json(['Mocktest given done...']);
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


        $result = MocktestUser::query()->with(['mocktest', 'givenAnswers', 'givenAnswers.question'])->findOrFail($id);


        if(!$result->show_result){
            return response()->json('Your result is not generated... Wait Sometime', 404);
        }

        $allUsers = MocktestUser::query()->where('mocktest_id', $result->mocktest_id)
            ->orderByDesc('mark')
            ->select('id')
            ->get();

        $position = $allUsers->search(function ($user) use ($result) {
                return $user->id === $result->id;
            }) + 1;

        $result->position = $position;
        $result->partisipants = $allUsers->count();

        $result->load('user:id,name,email,phone');

        $answers = MocktestAnswer::query()
            ->with('question')
            ->where('mocktest_user_id', $id)
            ->where('user_id', Auth::id())
            ->get();


        return response()->json([
            'ansCountes' => $result,
            'answerDetails' => $answers
        ]);


        return \response()->json([
            'ansCountes' => $givenAnsweres,
            'answerDetails' => $answers
        ], 200);

    }
}

