<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Lesson;
use Vimeo\Laravel\Facades\Vimeo;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class LessonController extends Controller
{
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $lesson = Lesson::create(
            Request::validate([
                'name'        => 'required|max:150',
                'chapter_id'  => 'nullable',
                'description' => 'required',
                'video'       => 'nullable',
                'course_id'   => 'required',
                'file'        => 'nullable',
                'status'      => 'boolean',
                'content'     => 'nullable',
            ])
        );

//        if (Request::hasFile('file')) {
//            $filePath = Request::file('file')->store('image', 'public');
//            $lesson->file = $filePath;
//            $lesson->save();
//        }

        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function show(Lesson $lesson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return Lesson
     */
    public function edit(Lesson $lesson)
    {
        return $lesson;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lesson $lesson)
    {
//        return Request::all();
        $lesson->update(
            Request::validate([
                'name'        => 'required|max:150',
                'description' => 'required',
                'chapter_id'  => 'nullable',
                'video'       => 'nullable',
                'course_id'   => 'required',
                'status'      => 'boolean',
                'content'     => 'nullable',
            ])
        );

        if (Request::hasFile('file')) {
            $delete_path = public_path().'/'.$lesson->files;
            if ($delete_path){
                @unlink($delete_path);
            }
            $filePath = Request::file('file')->store('image', 'public');
            $lesson->file = $filePath;
            $lesson->save();
        }

        return back();
    }

    public function updateLesson($id){
        $lesson = Lesson::findOrfail($id);
        $lesson->update(
            Request::validate([
                'name'        => 'required|max:150',
                'description' => 'required',
                'chapter_id'  => 'nullable',
                'video'       => 'nullable',
                'file'        => 'nullable',
                'course_id'   => 'required',
                'status'      => 'boolean',
                'content'     => 'nullable',
            ])
        );
        return back();
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return back();
    }


    function activation(Request $request, $id){
        $mocktest = Lesson::findOrFail($id);
        $mocktest->update([
            'status' => Request::input('show_status') == 'true' ? 1 : 0
        ]);
        return back();
    }
}
