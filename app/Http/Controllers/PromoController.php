<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\V1\UpdatePromoRequest;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Promo', [
            'promos' => Promo::query()
                ->when(request()->input('search'), function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%");
                })
                ->paginate(request()->input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($category) => [
                    'id' => $category->id,
                    'name' => $category->title,
                    'video' => $category->video
                ]),

            'filters' => request()->only(['search','perPage']),
            'url' => URL::route('promos.index'),
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'video' => 'required',
//            'image' => 'required'
        ]);
//
//        if ($request->hasFile('image')){
//            $image_path = $request->file('image')->store('image', 'public');
//        }

        Promo::create([
            'title' => $request->input('name'),
            'video' => $request->video,
//            'image' => $image_path ?? NULL,
        ]);

        return Redirect::route('promos.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Promo $promo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promo $promo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePromoRequest $request, Promo $promo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promo $promo)
    {
        $promo->delete();
        back();
    }
}
