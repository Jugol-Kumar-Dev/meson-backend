<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Http\Requests\UpdateChapterRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class ChapterController extends Controller
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
     * @param  \App\Http\Requests\StoreChapterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        Request::validate([
            'course_id' => 'required',
            'title' => 'required',
        ]);

        Chapter::create([
            'course_id' => Request::input('course_id'),
            'chapter_title' => Request::input('title'),
            'chapter_details' => Request::input('desc'),
            'status' => Request::input('status'),
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function show(Chapter $chapter)
    {
        return $chapter;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function edit(Chapter $chapter)
    {
        return $chapter;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateChapterRequest  $request
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Chapter $chapter)
    {
        $chapter->update([
            'chapter_title' => Request::input('title'),
            'chapter_details' => Request::input('desc'),
            'status' => Request::input('status'),
        ]);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Chapter $chapter)
    {
        $chapter->delete();
        return back();
    }
}
