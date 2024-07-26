<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        return inertia('SubCategory', [
            'sub_categories' => SubCategory::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($sub_category) => [
                    'id' => $sub_category->id,
                    'name' => $sub_category->name,
                    'category' => $sub_category->category->name,
                    'edit_s_cat' => URL::route('sub_categories.edit', $sub_category->id)
                ]),

            'filters' => Request::only(['search','perPage']),
            'categories' => Category::select('id','name')->get(),
            'url' => URL::route('sub_categories.index')
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
        SubCategory::create(
            Request::validate([
                'name' => 'required|max:50',
                'category_id' => 'required',
            ])
        );

        return Redirect::route('sub_categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function show(SubCategory $subCategory)
    {
        return $subCategory->load('category');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return SubCategory
     */
    public function edit(SubCategory $subCategory)
    {
        return $subCategory;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, SubCategory $subCategory)
    {
        $subCategory->update(Request::all());
        return Redirect::route('sub_categories.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubCategory  $subCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();

        return Redirect::route('sub_categories.index');
    }
}
