<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ChildCategory;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class ChildCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        return inertia('ChildCategory', [
            'child_categories' => ChildCategory::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($child_category) => [
                    'id' => $child_category->id,
                    'name' => $child_category->name,
                    'category' => $child_category->category->name,
                    'sub_category' => $child_category->sub_category->name,
                    'edit_c_cat' => URL::route('child_categories.edit', $child_category->id),
                ]),

            'filters' => Request::only(['search','perPage']),
            'categories' => Category::select('id','name')->get(),
            'sub_categories' => SubCategory::query()
            ->when(Request::input('category_id'), function ($query, $category_id) {
                $query->where('category_id', $category_id);
            })
            ->select('id','name','category_id')->get(),
            'url' => URL::route('child_categories.index')
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
        ChildCategory::create(
            Request::validate([
                'name' => 'required|max:50',
                'category_id' => 'required',
                'sub_category_id' => 'required',
            ])
        );

        return Redirect::route('child_categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ChildCategory  $childCategory
     * @return ChildCategory
     */
    public function show(ChildCategory $childCategory)
    {
        return $childCategory->load(['category', 'sub_category']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ChildCategory  $childCategory
     * @return ChildCategory
     */
    public function edit(ChildCategory $childCategory)
    {
        return $childCategory;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ChildCategory  $childCategory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ChildCategory $childCategory)
    {
        $childCategory->update(Request::all());
        return Redirect::route('child_categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ChildCategory  $childCategory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ChildCategory $childCategory)
    {
        $childCategory->delete();

        return Redirect::route('child_categories.index');
    }
}
