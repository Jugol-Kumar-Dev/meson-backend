<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\V1\UpdatePromoRequest;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Promo;
use App\Models\Review;
use App\Models\Slider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response|\Inertia\ResponseFactory
    {
        $userId = \request()->input('user_id');
        return inertia('Groups/Index', [
            'blogs' => Blog::query()->with(['user:id,name', 'comments', 'comments.user:id,photo,name'])
                ->when(\request()->input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->when($userId, function ($query, $search) {
                    $query->where('user_id', $search);
                })
                ->where('status', true)
                ->where('type', '=', 'post')
                ->latest()
                ->paginate(request()->input('perPage') ?? 12),

            'filters' => request()->only(['search', 'perPage']),
            'url' => URL::route('groups.index'),
        ]);

    }
    public function myPosts(): \Inertia\Response|\Inertia\ResponseFactory
    {
        return inertia('Groups/MyPosts', [
            'blogs' => Blog::query()
                ->with(['user:id,name', 'comments', 'comments.user:id,photo,name'])
                ->where('user_id', Auth::id())
                ->where('type', '=', 'post')
                ->latest()
                ->paginate(request()->input('perPage') ?? 12),

            'filters' => request()->only(['search', 'perPage']),
            'url' => URL::route('groups.index'),
        ]);

    }
    public function pendingPosts(): \Inertia\Response|\Inertia\ResponseFactory
    {
        return inertia('Groups/PendingPost', [
            'blogs' => Blog::query()->with(['user:id,name', 'comments', 'comments.user:id,photo,name'])
                ->where('status', false)
                ->where('type', '=', 'post')
                ->latest()
                ->paginate(request()->input('perPage') ?? 12),

            'filters' => request()->only(['search', 'perPage']),
            'url' => URL::route('groups.index'),
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
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'description' => 'nullable|max:300',
            'image' => 'nullable'
        ]);

        $image_path = null;
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('image', 'public');
        }

        $data['user_id'] = Auth::id();
        $data['image'] = $image_path;
        $blog = Blog::create($data);
        return back();
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
        $review = Blog::findOrFail($id);
        $review->delete();
        back();
    }



    public function saveComment($postId)
    {

        Comment::create([
            'blog_id' => $postId,
            'user_id' => Auth::id(),
            'message' => \request()->input('comment')
        ]);
        return back();
    }


    public function updateStatus($postId)
    {

        $blog = Blog::findOrFail($postId);
        $blog->status = true;
        $blog->save();
        return back();
    }
    public function updateCommentStatus($postId)
    {
        $blog = Blog::findOrFail($postId);
        $blog->is_like = request()->input('status') == 'false' ? 0 : 1;
        $blog->save();
        return back();
    }

    public function deletePosts(): \Illuminate\Http\RedirectResponse
    {
        $date = Carbon::now()->subDays(7);
        Blog::where('created_at', '<', $date)->delete();
        return back();
    }
}
