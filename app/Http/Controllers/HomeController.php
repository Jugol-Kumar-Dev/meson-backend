<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Faq;
use App\Models\Subscriber;
use App\Models\Wishlist;
use Carbon\Carbon;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function home()
    {
        $courses = Course::where('status', 'active')->get();
        $categories = Category::all();
        $blogs = Blog::where('is_featured', 1)->with(['user', 'category'])->get();
        return view('home', compact('courses', 'categories', 'blogs'));
    }

    public function about()
    {
        return view('about');
    }

    public function faq()
    {
        $faqs = Faq::all()->groupBy('category');
        return view('faq', compact('faqs'));
    }
    public function policy()
    {
        return view('trams_and_policy');
    }

    public function details($slug)
    {
        $faqs = Faq::all()->groupBy('category');
        $course = Course::withCount(['orders'])
            ->with(["user", "user.courses", "category.courses", "apiMocktests", "zoomes"])
            ->where('slug', $slug)->first();

        $lessons = $course->lessons()->orderBy('id', 'desc')->doesntHave('chapter')->get();
        $chapter = $course->chapters->load('videos');


        return view('details', compact('course', 'faqs', 'lessons', 'chapter'));
    }

    public function myfunction($course)
    {
        return $course;
    }
    public function contact()
    {
        return view('contact');
    }

    public function checkout($slug)
    {
        $faqs = Faq::all()->groupBy('category');
        $course = Course::where('slug', $slug)->first();
        return view('checkout', compact('course', 'faqs'));
    }

    public function applyCoupon(Request $request){
        $code = Coupon::where('code', $request->code)->first();
        if ($code != null){
            $users =  $code->users->where('pivot.user_id', Auth::id());
            if ($users->count() < $code->limit){
                if ($code->status){
                    if ($code->validate >= today()->format("Y-m-d")){
                        $msg = "Coupon Applied Successful....";
                        return redirect()->back()->with(compact('code', 'msg'))->withInput($request->all());
                    }else{
                        return back()->with('error', 'Coupon Code Is Expired..!')->withInput($request->all());
                    }
                }else{
                    return back()->with('error', 'Coupon Code Is Inactive..!')->withInput($request->all());
                }
            }else{
                return back()->with('error', 'Coupon Code Use Limit Expired...!')->withInput($request->all());
            }
        }else{
            return back()->with('error', 'Coupon Code is Not Valid..!')->withInput($request->all());
        }
    }

    public function bookmark(Request $request){

        if (!Auth::check()){
            return Response::json(["msg" => "unauthenticated", "status" => 403]);
        }

        Wishlist::create([
           'course_id' => $request->id,
           'user_id'   => Auth::id(),
        ]);

        return Response::json(["msg" => "Course Added Wishlist....!"], 200);

    }


    public function subscribeNewsfeed(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers'
        ]);

        if ($validator->fails()){
            return \response()->json($validator->errors(), 422);
        }
        Subscriber::create([
            'email' => $request->email
        ]);
        return \response()->json('Thank You For Subscribe '.config('app.name'), 200);
    }


    public function allApproveBlogs(Request $request)
    {
        if ($request->searchText != null){
            $blogs = Blog::where('publication_status', 1)
                ->where('title', 'like', "%{$request->searchText}%")
                ->orWhere('description', 'like', "%{$request->searchText}%")
                ->orWhere('content', 'like', "%{$request->searchText}%")
                ->with(['user', 'category'])->get();
        }else{
            $blogs = Blog::where('publication_status', 1)->with(['user', 'category'])->get();
        }
        $text = $request->searchText;
//        $blogs = Blog::where('publication_status', 1)->with(['user', 'category'])->get();
        return view('blogs', compact('blogs', 'text'));
    }

    public function singleBlog($slug){
        $blog = Blog::where('slug', $slug)
            ->with(['user', 'category', 'category.blogs','comments.user','comments.replayComments', 'comments' => function($comment){
            return $comment->where('comment_id', null);
        }])->first();
        $blog->view_count = $blog->view_count + 1;
        $blog->save();
        return view('single_blog', compact('blog'));
    }

    public function allCourses(Request $request){

        $courses = Course::where('status', 'active')->paginate(12);

        return view('course_list', compact('courses'));
    }




    public function getScheduledJobs()
    {
        new \App\Console\Kernel(app(), new Dispatcher());
        $schedule = app(Schedule::class);
        $scheduledCommands = collect($schedule->events());

        return $scheduledCommands;
    }






}
