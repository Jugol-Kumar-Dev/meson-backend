<?php

namespace App\Http\Controllers\Api\Frontend\V1;


use App\Http\Controllers\BusinessSettingController;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Course;
use App\Models\Page;
use App\Models\Review;

class HomeController extends Controller
{
    protected BusinessSettingController $bs;

    public function __construct()
    {
        $this->bs = new BusinessSettingController();
    }

    public function heraderCategories(): \Illuminate\Http\JsonResponse
    {
        $ids = json_decode($this->bs->get_setting('heroCats'));
        $category = Category::query()
            ->whereIn('id', $ids)
            ->select('id', 'name')
            ->get();

        return response()->json([
            'categories' => $category
        ]);
    }
    public function heroCategories(): \Illuminate\Http\JsonResponse
    {
        $ids = json_decode($this->bs->get_setting('heroCats'));

        $category = Category::query()
            ->whereIn('id', $ids)
            ->select('id', 'name', 'photo')
            ->get();

        return response()->json([
            'categories' => $category
        ]);
    }

    public function heroCourses(): \Illuminate\Http\JsonResponse
    {
        $ids = json_decode($this->bs->get_setting('heroCourses'));

        $courses = Course::query()
            ->whereIn('id', $ids)
            ->select(['id', 'name', 'cover', 'price'])
            ->get();

        return response()->json([
            'courses' => $courses
        ]);
    }

    public function topForCats(): \Illuminate\Http\JsonResponse
    {
        $ids = json_decode($this->bs->get_setting('topForCats'));
        $category = Category::query()
            ->whereIn('id', $ids)
            ->select('id', 'name', 'photo')
            ->take(4)
            ->get();
        return response()->json($category);
    }

    public function homeCoures(): \Illuminate\Http\JsonResponse
    {
        $items = collect(json_decode($this->bs->get_setting('homeCourses')));

        if ($items->count()) {
            $items->map(function ($item) {
                $item->courses = Course::query()
                    ->whereIn('id', $item->courses)
                    ->select('id', 'name', 'cover', 'price')
                    ->get();
            });
        }

        return response()->json($items);
    }

    public function secondHomeSection() : \Illuminate\Http\JsonResponse
    {
        $item = json_decode($this->bs->get_setting('secondSecond'));

        $item->categories = Category::query()
            ->whereIn('id', $item->categories)
            ->select('id', 'name', 'photo')
            ->withCount('courses')
            ->get();
        $item->courses = Course::query()
            ->whereIn('id', $item->courses)
            ->select('id', 'name', 'cover', 'price')
            ->get();

        return response()->json($item);
    }

    public function homeBlogs(): \Illuminate\Http\JsonResponse
    {
        $blogs = Blog::query()->with(['category', 'user'])
            ->where('type', '=', 'blog')
            ->select(['id','description', 'image', 'title'])
            ->latest()
            ->take(6)
            ->get();
        return response()->json($blogs);
    }

    public function singleBlogs($id): \Illuminate\Http\JsonResponse
    {
        $blog = Blog::query()->findOrFail($id);
        return response()->json($blog);
    }


    public function courseCateories(): \Illuminate\Http\JsonResponse
    {
        $categories = Category::query()->select(['id', 'name'])->get();
        return response()->json($categories);
    }

    public function categoryCourses(): \Illuminate\Http\JsonResponse
    {
        $courses = Course::query()
            ->when(request()->input('category'), function ($q, $search){
                $q->where('category_id', $search);
            })
            ->when(request()->input('search'), function ($q, $search){
                $q->where('name', 'LIKE', "%$search%");
            })
            ->select(['id', 'category_id', 'name', 'price', 'cover'])
            ->simplePaginate(16)
            ->withQueryString();

        return response()->json($courses);
    }

    public function studentReviews(): \Illuminate\Http\JsonResponse
    {
        $reviews = Review::query()
            ->select(['name', 'review'])
            ->latest()
            ->take(12)
            ->get();
        return response()->json($reviews);
    }


    public function footerPages(): \Illuminate\Http\JsonResponse
    {
        $reviews = Page::query()
            ->select(['id', 'title', 'slug'])
            ->latest()
            ->get();
        return response()->json($reviews);
    }

    public function singlePage($slug): \Illuminate\Http\JsonResponse
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->first();
        return response()->json($page);
    }


    public function getSettings(): \Illuminate\Http\JsonResponse
    {

        $data = request()->all();
        $response = [];
        foreach (explode(',', $data['name']) as $item) {
            $response[$item] = json_decode($this->bs->get_setting($item));
        }
        return response()->json($response);
    }
}


