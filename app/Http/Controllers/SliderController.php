<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\V1\UpdatePromoRequest;
use App\Models\Promo;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Sliders', [
            'sliders' => Slider::query()
                ->when(request()->input('search'), function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%");
                })
                ->paginate(request()->input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($category) => [
                    'id' => $category->id,
                    'name' => $category->title,
                    'photo' => $category->bg_image
                ]),

            'filters' => request()->only(['search','perPage']),
            'url' => URL::route('sliders.index'),
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
            'title' => 'required',
            'image' => 'required|mimes:jpg,png,gif|max:500'
        ]);

        if ($request->hasFile('image')){
            $image_path = $request->file('image')->store('image', 'public');
        }

        Slider::create([
            'title' => $request->input('title'),
            'bg_image' => $image_path,
        ]);

        return Redirect::route('sliders.index');
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
    public function destroy(string $slider)
    {
        $slider = Slider::findOrFail($slider);
        $slider->delete();
        back();
    }
}
