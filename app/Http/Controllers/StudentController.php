<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\GivenExam;
use App\Models\GivenMocktestAnswer;
use App\Models\Lesson;
use App\Models\Mocktest;
use App\Models\MocktestUser;
use App\Models\MoktestLink;
use App\Models\User;
use App\Models\Order;
use App\Models\Question;
use App\Models\Zoom;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Mockery\Mock;
use function Symfony\Component\Console\Style\text;


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        return inertia('Admin/User/Student', [
            'students' => User::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'photo' => $user->photo,
                    'active_on' => $user->created_at->format('d M Y'),
                ]),

            'filters' => Request::only(['search','perPage']),
            'url' => URL::route('students.index'),
            // 'can' => [
            //     'createUser' => Auth::user()->can('create', User::class)
            // ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $id;
//        return User::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function purchase_history()
    {
        return inertia('Student/Purchase', [
            'orders' => Order::query()
                ->where('user_id', Auth::user()->id)
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($order) => [
                    'id' => $order->id,
                    'course' => $order->course->name,
                    'photo' => $order->course->cover,
                    'payment_method' => $order->payment_method,
                    'pay_amount' => $order->pay_amount,
                    'currency' => $order->currency,
                    'created' => $order->created_at->format('d m Y'),
                ]),

            'filters' => Request::only(['search','perPage']),
            'url' => URL::route('purchase_history')
        ]);
    }

    public function course_list()
    {
        return inertia('Student/Course', [
            'orders' => Order::query()
                ->where('user_id', Auth::user()->id)
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($order) => [
                    'id' => $order->id,
                    'course' => $order->course?->name ?? '---',
                    'course_slug' => $order->course?->slug ?? '---',
                    'photo' => $order->course?->cover ?? '---',
                    'payment_method' => $order->payment_method,
                    'pay_amount' => $order->pay_amount,
                    'currency' => $order->currency,
                    'is_show' => $order->is_show,
                    'created' => $order->created_at->format('d m Y'),
                ]),

            'filters' => Request::only(['search','perPage']),
            'url' => URL::route('course_list')
        ]);
    }

    public function course_details(Course $course, $slug)
    {
        $course = Course::with(['mocktests'=>fn($mock)=>$mock->latest()])
            ->where('slug', $slug)
            ->first();

        return inertia('Student/CourseDetails', [
            'course' => $course,
            'url' => URL::route('course_list')
        ]);
    }

    public function student_profile()
    {
        return inertia('Student/Profile', [
            'user' => Auth::user(),
        ]);
    }
    public function student_profile_update()
    {
        $user = Auth::user();

        $user->name = Request::input('name');
        $user->email = Request::input('email');
        $user->phone = Request::input('phone');
        $user->gender = Request::input('gender');
        $user->married_status = Request::input('married_status');
        $user->about = Request::input('about');
        $user->dob = Request::input('dob');

        if (Request::hasFile('photo')) {
            $user->photo = Request::file('photo')->store('image', 'public');
        }

        $user->save();

        return Redirect::back();
    }

    public function mocktest_list($onlyData=false)
    {
        $user_id = Auth::user();
        $tests = Mocktest::where('show_student', 1)->where('status', 1)->latest()->get();




        $allTests = array();
        foreach ($tests as $test) {
//            $temp = $test->users()->firstWhere('user_id', $user_id->id);
            $allTests[] = [
                'mock' => $test,
//                'user' => $temp ?? ''
            ];
        }




        $courses = Order::query()->with('course')->where('user_id', Auth::id())->where('is_show', 1)->pluck('course_id');
        $courses = Course::query()->with('mocktests:id,name,status,exam_type,duration,end_time,start_time,total_q')->whereIn('id', $courses)->get();

        $allTests=[];
        foreach ($courses as $course){
            $mocks = $course->mocktests->where('status', 1);
            foreach ($mocks  as $mock){
                $allTests[] = [
                    'mock' => $mock,
                ];
            }
        }


        if ($onlyData){
            return $allTests;
        }

        return inertia('Student/MocktestList', [
            'mocktests' => $allTests
        ]);

    }

    public function mocktest_details($id)
    {
        $mocktest = Mocktest::findOrFail($id);

        return inertia('Student/MocktestDetails', [
            'mocktest' => $mocktest,
        ]);
    }



    public function mocktest_enroll($id)
    {

        $mocktest = Mocktest::findOrFail($id);

        if($mocktest->exam_type == 'main'){
            $givenId = MocktestUser::query()
                ->where('mocktest_id', $id)
                ->where('user_id', Auth::id())
                ->first();
            if($givenId){
                return back()->withErrors("You have already attended the live exam. Please check practice exams for further practice.");
            }
        }

        $qq = array();
        $givenStatus = true;
        if($mocktest){
            if($mocktest->exam_type == 'main'){
                if($mocktest->start_time <= now() && $mocktest->end_time >= now()){
                    $qq = Question::query()->whereIn('id', json_decode($mocktest->questions))->get();
                }else{
                    $givenStatus = false;
                }

            }else{
                $qq = Question::query()->whereIn('id', json_decode($mocktest->questions))->get();
            }
        }else{
            $givenStatus = false;
        }

        return inertia('Student/MocktestEnroll', [
            'questions' => $qq,
            'status' => $givenStatus,
            'mocktest' => $mocktest,
            'url' => URL::route('mocktest_enroll_store')
        ]);
    }

    public function mocktest_enroll_store()
    {
        $ansQuestions = Request::input('questions');
        $mocktest_id = Request::input('mocktest_id');
        $mocktest = Mocktest::find($mocktest_id);


        $givenId = MocktestUser::query()->where([
            'mocktest_id' => $mocktest->id,
            'user_id' => Auth::id(),
        ])->first();

        if(!empty($givenId) && $mocktest->exam_type == 'main'){
            return $this->mocktestResults($mocktest->id);
        }

        $mark = 0;
        $minusMark = 0;
        $total_correct = 0;
        $total_incarrect = 0;
        $total_question = $mocktest->total_q;

        $givenId = MocktestUser::query()->create([
            'mocktest_id' => $mocktest_id,
            'user_id' => Auth::id(),
        ]);

        $attatch = [];
        foreach ($ansQuestions as $answer) {
            $temp = Question::find($answer['id']);
            if ($temp && isset($answer['ans'])) {
                if($temp->answer === $answer['ans']){
                    $total_correct++;
                    $mark += $temp->mark;
                }else{
                    if(!empty($mocktest->minus_mark)){
                        $total_incarrect++;
                        $minusMark += (($temp->mark * $mocktest->minus_mark) / 100);
                    }
                }
            }
            $attatch []= [
                'mocktest_user_id' => $givenId->id,
                'user_id' => Auth::id(),
                'mocktest_id' => $mocktest_id,
                'qustion_id' => $temp->id,
                'given_ans' => $answer['ans'] ?? null,
            ];
        }

        $givenId->mark = $mark - $minusMark;
        $givenId->minus_mark =  $minusMark;
        $givenId->total_correct = $total_correct;
        $givenId->total_incaorrect = $total_incarrect;
        $givenId->update();

        GivenMocktestAnswer::query()->insert($attatch);

        return Redirect::route('single_mock_result', $givenId);
    }

    public function mocktestResults($id)
    {
//        $results = MocktestUser::query()
//            ->with('mocktest')
//            ->where('mocktest_id', $id)
//            ->where('user_id', Auth::id())
//            ->get();
//
//        return inertia('Student/MocktestResult',[
//            'results' => $results
//        ]);

        $mocktestUser = MocktestUser::query()
            ->where('mocktest_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if($mocktestUser){
            return $this->showResult($mocktestUser->id);
        }
        return back()->withErrors("First, take the exam. Afterwards, view your results.");

//
//        $mocktest = Mocktest::findOrFail($id);
//        $results = MocktestUser::query()
//            ->with('user')
//            ->where('mocktest_id', $mocktest->id)
//            ->orderBy('mark', 'desc')
//            ->get();
//
//
//
//        return inertia('Student/MocktestResult',[
//            'mock' => $mocktest,
//            'results' => $results
//        ]);
    }

    public function showResult($givenId, $onlyData=false)
    {
        $result = MocktestUser::query()->with(['mocktest', 'givenAnswers', 'givenAnswers.question'])->findOrFail($givenId);

        $allUsers = MocktestUser::where('mocktest_id', $result->mocktest_id)->orderByDesc('mark')->select('id')->get();
        $position = $allUsers->search(function ($user) use ($result) {
            return $user->id === $result->id;
        }) + 1;

        $result->position = $position;
        $result->partisipants = $allUsers->count();

        if($onlyData) return $result->load('user:id,name,email,phone');

        return inertia('Student/MockSingleResult',[
            'result' => $result
        ]);
    }


}
