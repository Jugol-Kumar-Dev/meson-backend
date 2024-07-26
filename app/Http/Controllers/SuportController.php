<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Suport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class SuportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        return inertia('Support', [
            'supports' => Suport::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($support) => [
                    'id' => $support->id,
                    'name' => $support->name,
                    'email' => $support->email,
                    'subject' => $support->subject,
                    'message' => $support->message,
                    'status' => $support->status,
                    'edit_url' => URL::route('support.edit', $support->id)
                ]),

            'filters' => Request::only(['search','perPage']),
            'url' => URL::route('support.index'),
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $data = Request::validate([
            'name'=>'required',
            'email'=>'required|email',
            'subject'=>'required|max:1000',
            'message'=>'required|max:50000',
        ]);

        if (Auth::check()){
            $data['user_id'] = Auth::id();
        }
        Suport::create($data);


        return redirect()->back()->with('success','Your message send success');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Suport  $suport
     * @return \Illuminate\Http\Response
     */
    public function show(Suport $suport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Suport  $suport
     * @return \Illuminate\Http\Response
     */
    public function edit(Suport $suport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Suport  $suport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Suport $suport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Suport  $suport
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Suport::findOrFail($id)->delete();
        return back();
    }
}
