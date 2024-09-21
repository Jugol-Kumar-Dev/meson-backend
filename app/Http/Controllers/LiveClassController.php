<?php

namespace App\Http\Controllers;

use App\Models\LiveClass;
use App\Models\Zoom;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LiveClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $classes = LiveClass::query()
            ->when($request->input('course_id'), function ($q, $id){
                $q->where('course_id', $id);
            })
            ->paginate(10)
            ->withQueryString();
        return response()->json($classes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|min:3|max:50',
            'start_time' => 'required',
            'link' => 'required',
            'agenda' => 'nullable',
            'course_id' => 'required'
        ]);
        $data['start_time']  = Carbon::parse($request->input('start_time'))->setTimezone('Asia/Dhaka');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('image', 'public');
        }
        LiveClass::create($data);
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Zoom  $zoom
     * @return \Illuminate\Http\Response
     */
    public function show(Zoom $zoom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Zoom  $zoom
     * @return \Illuminate\Http\Response
     */
    public function edit(Zoom $zoom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Zoom  $zoom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Zoom $zoom)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Zoom  $zoom
     * @return \Illuminate\Http\Response
     */
    public function destroy($meetingId)
    {
        //
    }
}
