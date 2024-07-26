<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        return inertia('Category', [
            'categories' => Category::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'photo' => $category->photo,
                    'edit_url' => URL::route('categories.edit', $category->id)
                ]),

            'filters' => Request::only(['search','perPage']),
            'url' => URL::route('categories.index'),
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
        // edit by jk,
        Request::validate([
            'name' => 'required|max:50',
            'photo' => 'required',
        ]);

        if (Request::hasFile('photo')){
            $image_path = Request::file('photo')->store('image', 'public');
        }

        Category::create([
            'name' => Request::input('name'),
            'photo' => $image_path,
        ]);

        return Redirect::route('categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return Category::where('slug', $slug)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Category::findOrfail($id);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return array|string
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
        Category::destroy($id);

        return Redirect::route('categories.index');
    }


    public function updatecheck(Request $request, $id){
        $category = Category::findOrfail($id);

        Request::validate([
            'name' => 'required|max:50',
            'photo' => 'required',
        ]);

        if (Request::hasFile('photo')){
            $image_path = public_path().'/'.$category->photo;
            @unlink($image_path);
            $image_path = Request::file('photo')->store('image', 'public');
        }else{
            $old_path = explode('/', Request::input('photo'));
            $image_path = $old_path[2]."/".$old_path[3];
        }

        $category->update([
            'name' => Request::input('name'),
            'photo' => $image_path,
        ]);

        return Redirect::route('categories.index');

    }












}
