<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use DateTime;
use DateTimeZone;
use App\Models\Course;
use App\Models\Category;
use App\Models\Lesson;
use App\Models\Mocktest;
use App\Models\Zoom;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Vimeo\Laravel\Facades\Vimeo;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        return inertia('Course/List', [
            'courses' => Course::query()->withCount('mocktests')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($course) => [
                    'id' => $course->id,
                    'name' => $course->name,
                    'description' => $course->description,
                    'cover' => $course->cover,
                    'video' => $course->video,
                    'status' => $course->status,
                    'price' => $course->price,
                    'active_on' => $course->active_on->format('d M Y'),
                    'category' => $course->category?->name,
                    'instructor' => $course->user?->name,
                    'show_url' => URL::route('courses.show', $course->id),
                    'edit_url' => URL::route('courses.edit', $course->id),
                ]),

            'filters' => Request::only(['search','perPage']),
            'url' => URL::route('courses.index')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        return inertia('Course/Create', [

            'categories' => Category::select('id', 'name')->get(),
            'url' => URL::route('courses.index')
            // 'can' => [
            //     'createUser' => Auth::user()->can('create', User::class)
            // ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        Request::validate([
//            'cover' => "required",
            'name' => "required",
            'description' => "required|max:300",
            'price' => "required",
//            'active_on' => "required",
        ]);

        $image_path = '';
        $files_path = '';

        if (Request::hasFile('cover')) {
            $image_path = Request::file('cover')->store('image', 'public');
        }
        if (Request::hasFile('files')) {
            $files_path = Request::file('files')->store('image', 'public');
        }

        $course = new Course();

        $course->name = Request::input('name');
        $course->description = Request::input('description');
        $course->content = Request::input('content');
        $course->video = Request::input('video');
        $course->cover = $image_path;
        $course->files = $files_path;
        $course->price = Request::input('price');
        $course->user_id = Auth::user()->id;
        $course->category_id = Request::input('category_id');
        $course->active_on = date("Y-m-d", strtotime(Request::input('active_on')));
        $course->access_time = Request::input('access_time');
        $course->access_type = Request::input('access_type') != "" ? Request::input('access_type') : NULL;
        $course->save();

//        $vimeo_url = '';
//        if (Request::hasFile('video')) {
//            $vimeo_url = Vimeo::upload(Request::file('video'), [
//                'name' => Request::input('name'),
//                'description' => Request::input('description')
//            ]);
//
//            $course->video = $vimeo_url;
//            $course->save();
//        }

        return Redirect::route('courses.index');
    }


    public function show(Course $course)
    {
        $mainMock = array();
        $cMock = array();
        $course = Course::with(['zoomes', 'mocktests'=>fn($mock)=>$mock->latest(), 'chapters', 'chapters.videos', 'orders', 'orders.user'])->withCount('orders')->findOrFail($course->id);
        $classMocktest = Mocktest::where('status', 1)
            ->latest()
            ->get();


        $zooms = new ZoomController();
        $zoomsData = $zooms->index($is_api=true);

        $lessonVideos = $course->lessons()->orderBy('id', 'desc')->doesntHave('chapter')->get();




        return inertia('Course/Show', [
            'course'    => $course ?? null,
            'mocktests' => $classMocktest ?? [],
            'lessons'   => Lesson::with('chapter')->where('course_id', $course->id)->orderBy('id', 'desc')->get() ?? [],
            'chapers'   => Chapter::all() ?? [],
            'lessonVideos' => $lessonVideos ?? [],
            'zooms'     => $zoomsData ?? [],
            'url'       => URL::route('courses.index'),
            'lesson_store_url' => URL::route('lessons.index'),
            "lesson_index" => URL::route('lessons.index'),
            'mocktests_create' => URL::route('mocktests.create'),
            'save_mocktest' => URL::route('save_mocktest', $course->id),
            'add_course_mitting' => URL::route('add_course_mitting'),
            'main_url' => URL::route('meetings.index'),
            'mock_delete_url' => URL::route('deleteMockFormCourse'),
            'chapter_url' => URL::route('chapter.index')

        ]);
    }

    public function saveMocktst(Request $request, $id){
        Request::validate([
//            "mock_id" => 'integer|unique:course_mocktest,moktest_link_id,'.Request::input('mock_id'),
            "mock_id" => 'integer|unique:course_mocktest,mocktest_id,NULL,id,course_id,'.Request::input('course_id'),
        ],[
            'mock_id.unique'=> "This Mocktst Already Exist In This Courses"
        ]);

        $mock = Mocktest::findOrFail(Request::input('mock_id'));
        $mock->courses()->attach(Request::input('course_id'));
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit(Course $course)
    {
        return inertia('Course/Update', [
            'course' => $course,
            'url' => URL::route('courses.index'),
            'categories' => Category::select('id', 'name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \never|void
     */
    public function update()
    {
        //
    }


    // course update methods

    public function updateCourses(Request $request, $id){

        Request::validate([
//            'cover' => "required",
            'name' => "required",
            'description' => "required|max:300",
            'price' => "required",
//            'active_on' => "required",
        ]);

        $course = Course::findOrFail($id);

        $image_path = '';
        $files_path = '';

        if (Request::hasFile('cover')) {
            $image_path = public_path().'/'.$course->cover;
            if ($image_path){
                @unlink($image_path);
            }
            $image_path = Request::file('cover')->store('image', 'public');
        }

        if (Request::hasFile('files')) {
            $delete_path = public_path().'/'.$course->files;
            if ($delete_path){
                @unlink($delete_path);
            }
            $files_path = Request::file('files')->store('image', 'public');
        }

        $course->name = Request::input('name');
        $course->description = Request::input('description');
        $course->content = Request::input('content');
        $course->cover = $image_path;
        $course->files = $files_path ?? null;
        $course->price = Request::input('price');
        $course->user_id = Auth::user()->id;
        $course->category_id = Request::input('category_id');
        $course->active_on = date("Y-m-d", strtotime(Request::input('active_on')));
        $course->access_time = Request::input('access_time');
        $course->access_type = Request::input('access_type') != "" ? Request::input('access_type') : NULL;
        $course->save();


        $vimeo_url = '';
        if (Request::hasFile('video')) {
            $vimeo_url = Vimeo::upload(Request::file('video'), [
                'name' => Request::input('name'),
                'description' => Request::input('description')
            ]);

            $course->video = $vimeo_url;
            $course->save();
        }



        return Redirect::route('courses.index');
    }


    public function updateCourseStatus($status, $id){
        Course::findOrFail($id)->update([
            'status' => $status
        ]);
        return back();
    }






    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return back();
    }

    public function courseZoomStore()
    {
        $meetingId =\request("zoomid");


        if (Auth::user()->role == 'admin' || Auth::user()->role == 'instructor') {
            if (Auth::user()->jwt_token != '' && Auth::user()->zoom_email != '') {
                $token = Auth::user()->jwt_token;
                $email = Auth::user()->zoom_email;
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.zoom.us/v2/users/$email",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "authorization: Bearer $token"
                    ),
                ));

                $profile = curl_exec($curl);
                $profile = json_decode($profile, true);

                $err = curl_error($curl);

                curl_close($curl);

                if (isset($profile['code']) && $profile['code'] != 200) {
                    // \Session::flash('delete', $profile['message']);
                    return redirect()->route('setting');
                }


                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.zoom.us/v2/meetings/$meetingId",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "authorization: Bearer $token"
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);
                $response = json_decode($response, true);


//                dd($response);
                if (isset($response['settings']['contact_email'])) {
                    $owner_id = $response['settings']['contact_email'];
                } else {
                    $owner_id = $response['host_email'];
                }



                $utc = isset($response['start_time']) ? $response['start_time'] : NULL;
                $dt = new DateTime($utc);
                $tz = new DateTimeZone($response['timezone']); // or whatever zone you're after
                $dt->setTimezone($tz);
                $meeting_time = $dt->format('Y-m-d H:i:s');

                if (isset($response['code']) && $response['code'] != 200) {
                    // \Session::flash('delete', $response['message']);
                    return redirect()->route('setting');
                }else{

                    $created_meeting = Zoom::create(
                        [
                            'meeting_id' => $response['id'],
                            'user_id' => Auth::user()->id,
                            'owner_id' => $owner_id,
                            'meeting_title' => $response['topic'],
                            'start_time' => $meeting_time,
                            'zoom_url' => $response['start_url'],
                            'join_url' => $response['join_url'],
                            'course_id' => \request('courseId'),
                            'type' => $response['type'],
                            'agenda' => $response['agenda'],
                        ]
                    );
//                    return $created_meeting;
                }

                curl_close($curl);

            } else {
                return redirect()->route('setting')->with('delete', 'Zoom Token or email not found !');
            }
        } else {
            return abort(403, 'Unauthorized action.');
        }

    }

    public function deleteZoomFormCourse($id){
        $zoom = Zoom::where('meeting_id', $id)->first();
        $zoom->delete();
        return back();
    }


    public function courseLessonUpadate()
    {
        # code...
    }

    public function deleteMockFormCourse(){
//        return Request::all();
        $course = Course::findOrFail((int)Request::input('course_id'));
        $temp = $course->mocktests()->firstWhere('mocktest_id', Request::input('mock_id'));
        $temp->pivot->delete();
        return back();
    }

}
