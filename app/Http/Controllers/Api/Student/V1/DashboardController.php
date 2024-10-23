<?php

namespace App\Http\Controllers\Api\Student\V1;


use App\Http\Controllers\BusinessSettingController;
use App\Http\Controllers\Controller;
use App\Models\LiveClass;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class DashboardController extends Controller
{
    public function todayData(): \Illuminate\Http\JsonResponse
    {
        $order = Order::query()->where('user_id', Auth::id())->distinct()->pluck('course_id')->toArray();
        $ids = DB::table('mocktest_user')
            ->distinct()
            ->where('exam_type', '=', 'live')
            ->where('user_id', Auth::id())
            ->pluck('mocktest_id');

        $data = DB::table('course_mocktest')
            ->join('mocktests', 'course_mocktest.mocktest_id', '=', 'mocktests.id')
            ->join('courses', 'course_mocktest.course_id', '=', 'courses.id')
            ->select(
                'courses.name as course_name',
                'courses.id as course_id',
                'course_mocktest.*',
                'mocktests.*')
            ->whereIn('course_mocktest.course_id', $order)
            ->whereNotIn('mocktests.id', $ids)
            ->where('start_time', '<=', Carbon::now())
            ->where('end_time', '>=', Carbon::now())
            ->get();



        $startOfToday = Carbon::today();          // 00:00:00 today
        $endOfToday = Carbon::tomorrow();         // 00:00:00 tomorrow

        $course = [(int) Request::input('course_id')] ?? $order;
        $classes = LiveClass::query()
            ->with(['course:id,name'])
            ->whereIn('course_id', $course)
            ->where('start_time', '>=', $startOfToday) // Classes starting today
            ->where('start_time', '<', $endOfToday)    // Classes starting before tomorrow
            ->latest()
            ->get();


        $bs = new BusinessSettingController();

        return response()->json([
            'classes' => $classes,
            'mocktests' => $data,
            'notice' => json_decode($bs->get_setting('notice'))
        ]);
    }

    public function myTrx(): \Illuminate\Http\JsonResponse
    {
        $trx = Transaction::query()
            ->with(['course', 'order'])
            ->where('user_id', Auth::id())
            ->get();
        return response()->json($trx);
    }
    public function singleTrx($id): \Illuminate\Http\JsonResponse
    {
        $trx = Transaction::query()
            ->with(['course', 'order', 'user'])
            ->where('trx', $id)
            ->first();

        return response()->json($trx);
    }

}

