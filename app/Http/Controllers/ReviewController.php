<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\V1\UpdatePromoRequest;
use App\Models\Promo;
use App\Models\Review;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Review', [
            'reviews' => Review::query()
                ->when(request()->input('search'), function ($query, $search) {
                    $query->where('review', 'like', "%{$search}%");
                })
                ->paginate(request()->input('perPage') ?? 10)
                ->withQueryString(),

            'filters' => request()->only(['search','perPage']),
            'url' => URL::route('review.index'),
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
        Review::create([
            'name' => $request->input('name'),
            'review' => $request->input('review')
        ]);

        return Redirect::route('review.index');
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
    public function destroy(string $id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        back();
    }
}
