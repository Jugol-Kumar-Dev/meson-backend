<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Question;
use App\Models\QuestionCategory;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        return inertia('Question/List', [
            'questions' => Question::query()
                ->with('category')
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%");
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
                ]),

            'categories' => Category::all(),

            'filters' => Request::only(['search', 'perPage']),
            'url' => URL::route('questions.index'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        return inertia('Question/Create', [
            'categories' => QuestionCategory::all(),
            'url' => URL::route('questions.index'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        Request::validate([
           'category_id' => 'required',
           'title' => "required",
           'answer' => "required",
           'mark' => 'required',
            'a'=> 'required',
            'b'=> 'required',
            'c'=> 'required',
            'd'=> 'required',
//            'e'=> 'required',
            'feedback' => 'required'
        ]);



        $data = Request::only(
            'title',
            'category_id',
            'sub_category_id',
            'child_category_id',
            'a',
            'b',
            'c',
            'd',
            'e',
            'answer',
            'mark',
            'feedback'
        );


        Question::create($data);

        return Redirect::route('questions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return Question
     */
    public function show(Question $question)
    {
        return $question->load(['category', 'sub_category', 'child_category']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Inertia\Response
     */
    public function edit(Question $question)
    {


        return inertia('Question/Edit', [
            'question' => $question,
            'categories' => QuestionCategory::all(),
            'url' => URL::route('questions.update', $question->id),
        ]);


//        return Inertia::render('Question/Edit', [
//            'categories' => Category::select('id', 'name')->get(),
//            'sub_categories' => SubCategory::query()
//                ->when(Request::input('category_id'), function ($query, $category_id) {
//                    $query->where('category_id', $category_id);
//                })
//                ->select('id', 'name', 'category_id')->get(),
//            'child_categories' => ChildCategory::query()
//                ->when(Request::input('sub_category_id'), function ($query, $sub_category_id) {
//                    $query->where('sub_category_id', $sub_category_id);
//                })
//                ->select('id', 'name', 'sub_category_id')->get(),
//            'url' => URL::route('questions.index'),
//            'subbycat_url' => URL::route('subbycat'),
//            'childbysubcat_url' => URL::route('childbysubcat'),
//           'quotation' => $question,
//        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Question $question)
    {
        $question->update(
            Request::only(
                'title',
                'category_id',
                'sub_category_id',
                'child_category_id',
                'a',
                'b',
                'c',
                'd',
                'e',
                'answer',
                'mark',
                'feedback'
            )
        );
        return Redirect::route('questions.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Question $question)
    {
        try{
            $question->delete();
        }catch (\Exception $exception){
            session()->flash('error',"Question Exist An Mocktest" );
            return back()->withErrors("Question Exist An Mocktest");
        }
    }
}
