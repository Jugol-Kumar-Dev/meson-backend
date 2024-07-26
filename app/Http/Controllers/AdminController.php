<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        return inertia('Admin/User/Admin', [
            'admins' => Admin::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($admin) => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'photo' => $admin->photo,
                    'active_on' => $admin->created_at->format('d M Y'),
                ]),

            'filters' => Request::only(['search','perPage']),
            'url' => URL::route('admins.index')
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
    public function store()
    {
        $data = Request::validate([
            'name' => 'required|max:50',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|min:8',
        ]);

        $user = Admin::create($data);

        return Redirect::route('admin.index');
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
        Admin::destroy($id);

        return Redirect::route('admin.index');
    }

    public function resetPassword(){

        Request::validate([
            'oldPassword' => 'required',
            'newPassword' => 'required|min:6|max:8',
            'retypePassword' => 'required|same:newPassword|min:6|max:8'
        ]);

//
//        $this->validate($request,[
//            'current_password' => 'required',
//            'password' => 'required|confirmed',
//        ]);

        $user = Auth::user();
        $hashedPassword = $user->password;

        if ( Hash::check(Request::input('oldPassword') , $hashedPassword)){
            if (!Hash::check(Request::input("newPassword"), $hashedPassword)){
                $user->update([
                    'password' => Request::input("newPassword")
                ]);
                Auth::logout();
                return redirect()->route('login');
            }else{
//                return back()->with('success', 'Verification Mail Send Successful.');
                return back()->with('success', 'New Password Can Not Be Same As Same Password');
            }
        }else{
            return back()->with('success', 'Current Password Not Match');
        }
        return back();


    }
}
