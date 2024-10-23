<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {


        return inertia('Transaction', [
            'transactions' => Transaction::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($transaction) => [
                    'id' => $transaction->id,
                    // 'course' => $transaction->course->name,
                    // 'user' => $transaction->user->name,
                    'trx' => $transaction->trx,
                    'method' => $transaction->method,
                    'amount' => $transaction->amount,
                    'date' => $transaction->created_at->format('d M Y'),
                ]),
            'total' => Transaction::sum('amount'),
            'filters' => Request::only(['search', 'perPage']),
            'url' => URL::route('transactions.index')
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Transaction $transaction
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function show($trx): \Inertia\Response|\Inertia\ResponseFactory
    {
        $trx = Transaction::query()
            ->with('order','order.course','user', 'order.orderDetails')
            ->where('trx', $trx)->first();
        return inertia('ShowTransaction',[
            'trx' => $trx,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Transaction $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
