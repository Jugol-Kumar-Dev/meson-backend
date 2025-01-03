<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Mocktest;
use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class DashboardController extends Controller
{
    public function admin()
    {
        $lastYear = Carbon::now()->subYear();
        return inertia('Dashboard', [
            'total_sale' => Transaction::count(),
            'total_student' => User::where('role', 'student')->count(),
            'total_course' => Course::count(),
            'total_sale_amount' => Transaction::sum('amount'),

        'monthlyData' => Transaction::query()
            ->selectRaw('MONTHNAME(created_at) as month, SUM(amount) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
        ]);
    }

    public function student()
    {
        $stdClt = new StudentController();
        return inertia('Student/Dashboard', [
            'report' =>[
                'total_wishlist_course' => Auth::user()->witchLists->count(),
                'total_transactions'    => Auth::user()->transactions->count(),
                'total_courses'         => Auth::user()->orders->count(),
                'total_subscriptions'   => Auth::user()->subscriptions->count(),
                'total_mok'             => count($stdClt->mocktest_list(true)),
                'total_mocktests'       => Auth::user()->mocktests->count(0)
            ]
        ]);
    }

    public function instructor()
    {
        return "called here";
        return inertia('Dashboard');
    }
}
