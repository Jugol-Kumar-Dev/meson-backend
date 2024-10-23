<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ClassMakerApi;
use App\Http\Controllers\API\ClassMarkerClient;
use App\Models\Category;
use App\Models\GivenMocktestAnswer;
use App\Models\Mocktest;
use App\Models\MocktestAnswer;
use App\Models\MocktestUser;
use App\Models\MoktestLink;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\SubCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use function Spatie\Ignition\Config\theme;

class MocktestController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */


    public function index(){
        return inertia('Mocktest/List', [
            'mocktests' => Mocktest::query()
                ->withCount('questions')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($mocktest) => [
                    'id' => $mocktest->id,
                    'name' => $mocktest->name,
                    'questionCount' => $mocktest->questions_count,
                    'exam_type' => $mocktest->exam_type,
                    'total_q' => $mocktest->total_q,
                    'start_time' => $mocktest->start_time,
                    'end_time' => $mocktest->end_time,
                    'duration' => $mocktest->duration,
                    'student_show' => $mocktest->show_student,
                    'status' => $mocktest->status,
                ]),

            'filters' => Request::only(['search','perPage']),
            'url' => URL::route('mocktests.index')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        $categories = QuestionCategory::query()
            ->get();

        return inertia('Mocktest/Create', [
            'categories' => $categories,
            'url' => URL::route('mocktests.index'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()//: \Illuminate\Http\RedirectResponse
    {
        $data = Request::validate([
            'name' => 'required|max:150',
            'total_q' => 'nullable',
            'question_type' => 'required',
            'about' => 'nullable',
            'examType' => ['required'],
            'duration' => ['required']
        ]);

        $data['duration'] = Request::input('duration');
        if(Request::input('examType')  == 'main'){
            Request::validate([
                'start_time' => ['required'],
                'end_time' => ['required']
            ]);
            $start_time =  Carbon::parse(Request::input('start_time'))->setTimezone('Asia/Dhaka');
            $end_time = Carbon::parse(Request::input('end_time'))->setTimezone('Asia/Dhaka');
            $data['start_time'] = $start_time;
            $data['end_time'] = $end_time;
        }

        $data['exam_type'] = Request::input('examType');
        $data['user_id'] = Auth::user()->id;
        $data['questions'] = json_encode(Request::input("checkedQuestions"));
        $data['minus_mark'] = Request::input('minus_mark');
        $data['pass_mark'] = Request::input('pass_mark');

        Mocktest::create($data);

/*        if(Request::input("checkedQuestions") || count(Request::input("checkedQuestions"))){
            $arrayGroup = array_map(function ($item){
                return ['qustion_id' => $item];
            }, Request::input("checkedQuestions"));

            $mockTest->questions()->attach($arrayGroup);
        }*/

        return Redirect::route('mocktests.index');
    }

    public function createArrayGroups($items)
    {
        $added = array();
        foreach($items as $item){
            $id = $item['id'];
            $added[$id] = ['question' => $item['take']];
        }

        return $added;
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Mocktest  $mocktest
     * @return Mocktest
     */
    public function show(Mocktest $mocktest)
    {
        return $mocktest->load(['sub_categories', 'sub_categories.questions' => function($question) {
            $question->limit('sub_categories.pivot.question');
        }]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Mocktest  $mocktest
     * @return \Illuminate\Http\Response
     */
    public function edit(Mocktest $mocktest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Mocktest  $mocktest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mocktest $mocktest)
    {
        //
    }

    function publishedForStudent(Request $request, $id){

          $mocktest = Mocktest::findOrFail($id);

//          return $mocktest;
          $mocktest->update([
             'show_student' => Request::input('show_status') == 'true' ? 1 : 0
          ]);
          return back();
    }

    function activation(Request $request, $id){
          $mocktest = Mocktest::findOrFail($id);


          $mocktest->update([
             'status' => Request::input('show_status') == 'true' ? 1 : 0
          ]);
          return back();
    }

    public function singleMocktest(){

        $mock = MoktestLink::findOrFail(Request::input('mock-id'));
        Auth::user()->apiMocktests()->attach($mock->id);
        return view('exam', compact('mock'));

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Mocktest  $mocktest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Mocktest $mocktest)
    {

        try{
            $mocktest->delete();
        }catch (\Exception $exception){
            session()->flash('error',"Question Exist An Mocktest" );
            return back()->withErrors("Mocktest Exist An Student Or Courses");
        }
        return back();
    }


    public function mockFullDetails($id)
    {
        $mocktest = Mocktest::findOrFail($id);
        $mocktest->users =  MocktestUser::query()
            ->with('user:id,name,phone')
            ->where('mocktest_id', $mocktest->id)
            ->orderBy('mark', 'desc')
            ->get();
        return inertia('Mocktest/Show', [
            'mock' => $mocktest,
        ]);
    }



    public function getAllQuestionsForMocktests()
    {
        $questions = Question::query()
            ->with('category')
            ->when(Request::input('search'), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when(Request::input('categories'), function ($query, $cats){
                $query->whereIn('category_id', $cats);
            })
            ->latest()
            ->paginate(Request::input('perPage') ?? 10)
            ->withQueryString()
            ->through(fn ($question) => [
                'id' => $question->id,
                'title' => $question->title,
                'answer' => $question->answer,
                'mark' => $question->mark,
                'category' => $question->category,
                'edit_question' => URL::route('questions.edit', $question->id)
            ]);

        return response()->json($questions);
    }

    public function getMocktestStudentResult()
    {


        $result = MocktestUser::query()->with(['mocktest', 'givenAnswers', 'givenAnswers.question'])->findOrFail(\request()->input('id'));

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
            ->where('mocktest_user_id', $result->id)
            ->where('user_id', $result->user_id)
            ->get();

        return inertia('Mocktest/ShowResult',[
            'answers' => $answers,
            'result' => $result
        ]);


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

    }


}
