<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Faq;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        return inertia('Faqs', [
            'faqs' => Faq::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($faq) => [
                    'id' => $faq->id,
                    'title' => $faq->title,
                    'category' => $faq->category,
                    'description' => $faq->description,
                    'created_at' => $faq->created_at->format('D,M-Y'),
                    'edit_url' => URL::route('coupons.edit', $faq->id)
                ]),

            'filters' => Request::only(['search','perPage']),
            'url' => URL::route('faqs.index'),
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
    public function store(Request $request)
    {
        Faq::create(
            Request::validate([
                'title' => 'required|max:255',
                'description' => 'required',
                'category' => 'required'
            ])
        );
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function show(Faq $faq)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function edit(Faq $faq)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faq $faq)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Faq  $faq
     * @return \Illuminate\Http\Response
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();
        return back();
    }
}
