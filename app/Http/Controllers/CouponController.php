<?php

namespace App\Http\Controllers;

use App\Models\Coupon;;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {

        return inertia('Coupon', [
            'coupons' => Coupon::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($coupon) => [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'status' => $coupon->status,
                    'name' => $coupon->name,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'limit' => $coupon->limit,
                    'validate' => $coupon->validate,
                ]),

            'filters' => Request::only(['search','perPage']),
            'url' => URL::route('coupons.index'),
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
        Coupon::create([
            'creator' => Auth::id(),
            'name' => Request::input('name'),
            'code' => Request::input('code'),
            'value' => Request::input('price'),
            'type' => Request::input('type'),
            'limit' => Request::input('limit'),
            'validate' => date("Y-m-d", strtotime(Request::input('validate'))),
        ]);

        return Redirect::route('coupons.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return  back();
    }

    function activation(Request $request, $id){
        $mocktest = Coupon::findOrFail($id);
        $mocktest->update([
            'status' => Request::input('show_status') == 'true' ? 1 : 0
        ]);
        return back();
    }


}
