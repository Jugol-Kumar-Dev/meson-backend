<?php

namespace App\Http\Controllers\Api\Student\V1;


use App\Http\Controllers\Controller;
use App\Http\Controllers\ZoomController;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Mocktest;
use App\Models\Order;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
        $course = Course::query()
            ->withCount(['mocktests', 'liveClasses', 'chapters'])
            ->findOrFail($id);
        $course->instractors = User::query()->whereIn('id', json_decode($course->instractors))->get();
        return response()->json($course);
    }


}

