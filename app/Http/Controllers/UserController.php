<?php

namespace App\Http\Controllers;

use App\Http\Properties;
use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use function Money\sum;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function student()
    {
        return inertia('User/Student', [
            'students' => User::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->with(['orders', 'orders.course'])
                ->where('role', 'student')
                ->latest()
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($student) => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'phone' => $student->phone,
                    'email' => $student->email,
                    'photo' => $student->photo,
                    'certificate' => $student->certificate,
                    'is_active' => $student->is_active,
                    'active_on' => $student->created_at->format('d M Y'),
                    'courses' => $student->orders,
                    "show_url" => URL::route('student.show', $student->id),
                    'delete_url' => URL::route('student.delete', $student->id),
                ]),

            'filters' => Request::only(['search','perPage']),
            'url'     => URL::route('student.store'),
            'main_url' => URL::route('student.list'),

            // 'can' => [
            //     'createUser' => Auth::user()->can('create', User::class)
            // ]
        ]);
    }
    public function instructor()
    {
        return inertia('User/Instractors', [
            'instructors' => User::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->where('role', 'instructor')
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString(),

            'filters' => Request::only(['search','perPage'])
            // 'can' => [
            //     'createUser' => Auth::user()->can('create', User::class)
            // ]
        ]);
    }
    public function admin()
    {
        return inertia('User/Admin', [
            'admins' => User::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->where('role', 'admin')
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($admin) => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'phone' => $admin->phone,
                    'email' => $admin->email,
                    'photo' => $admin->photo,
                    'active_on' => $admin->created_at->format('d M Y'),
                ]),

            'filters' => Request::only(['search','perPage'])
            // 'can' => [
            //     'createUser' => Auth::user()->can('create', User::class)
            // ]
        ]);
    }
    public function profile()
    {
        return inertia('User/Profile', [
            'user' => Auth::user(),
            // 'can' => [
            //     'createUser' => Auth::user()->can('create', User::class)
            // ]
        ]);
    }
    public function profile_update()
    {

        $user = User::findOrFail(Auth::user()->id);

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
    public function setting()
    {
        return inertia('User/Setting', [
            'user' => Auth::user(),
            // 'can' => [
            //     'createUser' => Auth::user()->can('create', User::class)
            // ]
        ]);
    }
    public function setting_update()
    {
        $user = User::findOrFail(Auth::user()->id);

        $user->zoom_email = Request::input('zoom_email');
        $user->jwt_token = Request::input('jwt_token');

        $user->save();

        return Redirect::back();
    }


    public function showStudent($id)
    {
        $courses =  Course::where('status', 'active')->withCount("zoomes")->get();
        $zooms = 0;
        foreach ($courses as $c){
            $zooms = $zooms + $c->zoomes_count;
        }
        $user = User::findOrFail($id)->load(['courses', 'mocktests', 'transactions']);


        return inertia('User/StudentProfile', [
            'user' => $user,
            'courses' => Order::where('user_id', $id)->with('course')->get(),
            'allCourses' => Course::where('status', 'active')->get(),
            'create' => URL::route('paypal.pay'),
            'zoomes' => $zooms,
            'edit_url' => URL::route('student.edit', $user->id)
        ]);
    }


    public function storeStudent(Request $request){
        $data = Request::validate([
            'name' => 'required',
            'phone' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
//            'certificate' => 'required',
//            'photo' => 'required',
        ]);

        if (Request::hasFile('photo')){
            $image_path = Request::file('photo')->store('image', 'public');
        }

        $url ="";
        if (Request::hasFile('certificate')) {
            $url = Request::file('certificate')->store('image', 'public');
        }

        $data['photo'] = $image_path ?? null;
//        $data['certificate'] = $url ?? null;
        User::create($data);

        return Redirect::back();

    }

//    public function updateStudent(Request $request, $id){
//
//        return Request::all();
//
//
//        $data = Request::validate([
//            'name' => 'required',
//            'phone' => 'required|unique:users',
//            'email' => 'required|email|unique:users',
//            'password' => 'required|string|min:8',
//            'certificate' => 'required',
//        ]);
//
//        $url ="";
//
//        if (Request::hasFile('certificate')) {
//            $url = Request::file('certificate')->store('image', 'public');
//        }
//        $data['certificate'] = $url ?? null;
//        User::create($data);
//
//        return Redirect::back();
//
//    }

    public function showSingleStudent($id){
        return User::findOrFail($id);
    }

    public function updateStudent(Request $request, $id){
        $student = User::findOrFail($id);


//        return Request::all();


        $data = Request::validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone,'.$id,
            'email' => 'required|email|unique:users,email,'.$id,
//            'password' => 'required|string|min:8',
//            'certificate' => 'required',
        ]);

        if (Request::hasFile('photo')){
            $image_path = public_path().'/'.$student->photo;
            @unlink($image_path);
            $image_path = Request::file('photo')->store('image', 'public');
        }else{
            $old_path = explode('/', Request::input('photo'));
            $image_path = $old_path[2]."/".$old_path[3];
        }

        if (Request::hasFile('certificate')){
            $certificate = public_path().'/'.$student->certificate;
            @unlink($certificate);
            $certificate = Request::file('certificate')->store('image', 'public');
        }else{
            $old_path = explode('/', Request::input('certificate'));
            $certificate = $old_path[2]."/".$old_path[3];
        }



        $data['photo'] = $image_path;
        $data['certificate'] = $certificate;

        $student->update($data);

        return back();
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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


    public function store()
    {

        $data = Request::validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required'
        ]);

        User::create($data);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $student = User::findOrFail($id);
        $student->delete();
        return back();

    }

    public function activation(Request $request, $id){
        $user = User::findOrFail($id);
        $user->update([
            'is_active' => Request::input('show_status') == 'true' ? 1 : 0
        ]);
        return back();
    }

    public function assignCourseActivationStatus(Request $request, $id){
        $order = Order::findOrFail($id);
        $order->update([
            'is_show' => Request::input('show_status') == 'true' ? 1 : 0
        ]);
        return back();
    }

    public function deleteAssignCourse(Request $request, $id){
        $order = Order::findOrFail($id);
        $order->delete();
        return back();
    }

    public function showAssignCourse($id){
        return Order::findOrFail($id);
    }

    public function updateAssignCourse($id){
        $request = \request();

        $order = Order::findOrFail($id);
        $order->is_show = $request->is_show;
        $order->duration = $request->access_time;
        $order->duration_type = $request->access_type;
        $order->enroll_start = date(Properties::$enrollDateFormat, strtotime($request->enroll_start));
        $order->enroll_expire = date(Properties::$enrollDateFormat, strtotime($request->enroll_end));
        $order->save();
        return Response::json("successfully udpated", 200);
    }

}
