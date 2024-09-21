<?php

namespace App\Http\Controllers\Api\Student\V1;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Comment;
use App\Models\LiveClass;
use App\Models\Order;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class LiveClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()//: \Illuminate\Http\JsonResponse
    {
        $order = Order::query()->where('user_id', Auth::id())->distinct()->pluck('course_id')->toArray();
        $course = [(int) Request::input('course_id')] ?? $order;

        $classes = LiveClass::query()
            ->with(['course:id,name'])
            ->whereIn('course_id', $course)
            ->latest()
            ->paginate(Request::input('perPage') ?? 12)
            ->withQueryString();
        return response()->json($classes);
    }

}
