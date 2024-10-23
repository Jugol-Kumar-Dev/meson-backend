<?php

namespace App\Http\Controllers\Api\Student\V1;


use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function myCourses(): \Illuminate\Http\JsonResponse
    {
        $orders = Order::query()
            ->distinct()
            ->with(['course:id,name,cover'])
            ->where('user_id', Auth::id())
            ->select('course_id')
            ->simplePaginate(12);
        return response()->json($orders);
    }

    public function courseContent($id): \Illuminate\Http\JsonResponse
    {
        $chapters = Chapter::query()
            ->with(['course:id,name,cover', 'videos'])
            ->where('course_id', $id)
            ->get();

        return response()->json($chapters);
    }

    public function courseDetails($id): \Illuminate\Http\JsonResponse
    {
        $order = Order::query()->where('course_id', $id)->where('user_id', Auth::id())->first();
        if ($order->enroll_expire < now()) {
            return response()->json(
                [
                    'title' => 'Course Expired',
                    'message' => 'Your course date has expired. Please renew your subscription to continue enjoying our services.'
                ], 404);
        }
        if (!$order->is_show) {
            return response()->json([
                'title' => 'Course Inactive',
                'message' => 'Your Course Is Inactive Now, Please Contact Administrator'
            ], 404);
        }
        $course = Course::query()
            ->withCount(['mocktests', 'liveClasses', 'chapters'])
            ->findOrFail($id);

        if ($course->instractors && is_array(json_decode($course->instractors))) {
            $course->instractors = $course->instractors ? User::query()->select(['name', 'photo', 'about'])
                ->whereIn('id', json_decode($course->instractors))->get() : null;
        }
        $course->inclues = $course->inclues ? json_decode($course->inclues) : NULL;
        $course->features = $course->features ? json_decode($course->features) : NULL;
        $course->faqs = $course->faqs ? json_decode($course->faqs) : NULL;
        return response()->json($course);
    }


}

