<?php

namespace App\Http\Controllers\Api\Student\V1;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $blogs = Blog::query()->with(['user:id,name', 'comments', 'comments.user:id,photo,name'])
            ->when(Request::input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->where('type', '=','post')
            ->latest()
            ->paginate(Request::input('perPage') ?? 12)
            ->withQueryString();
        return response()->json($blogs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function create()
    {
        return inertia('Blog/Create', [
            'categories' => Category::select('id', 'name')->get(),
            'tags' => Tag::all('name'),
            'url' => URL::route('blogs.index')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function store()//: \Illuminate\Http\JsonResponse
    {
        $data = Request::validate([
            'description' => 'nullable|max:300',
            'image' => 'nullable'
        ]);

        $image_path = null;
        if (Request::hasFile('image')) {
            $image_path = Request::file('image')->store('image', 'public');
        }

        $data['user_id'] = Auth::id();
//        $data['status'] = Request::input('p_status');
        $data['image'] = $image_path;
        $blog = Blog::create($data);
        return response()->json($blog);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Blog $blog
     * @return Blog
     */
    public function show(Blog $blog)
    {
        return $blog;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Blog $blog
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function edit(Blog $blog)
    {

        return inertia('Blog/Update', [
            'blog' => $blog,
            'categories' => Category::select('id', 'name')->get(),
            'tags' => Tag::all('name'),
            'url' => URL::route('blogs.index'),
            'update' => URL::route('blogs.update_blog', $blog->id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Blog $blog
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Blog $blog)
    {
        //
    }

    public function updateBlog($id)
    {

        $blog = Blog::findOrFail($id);
        $data = Request::validate([
            'title' => 'required|max:250|min:5',
            'category_id' => 'required',
            'description' => 'nullable|max:300',
            'content' => 'required',
            'tags' => 'array',
            'cover' => 'nullable'
        ]);

        $image_path = '';
        if (Request::hasFile('cover')) {
            $image_path = public_path() . '/' . $blog->cover;
            if ($image_path) {
                @unlink($image_path);
            }
            $image_path = Request::file('cover')->store('image', 'public');
        } else {
            if (Request::input('cover') != null) {
                $old_path = explode('/', Request::input('cover'));
                $image_path = $old_path[2] . "/" . $old_path[3];
            } else {
                $image_path = NULL;
            }
        }

        $data['user_id'] = Auth::id();
        $data['tags'] = json_encode(Request::input('tags'));
        $data['publication_status'] = Request::input('p_status');
        $data['is_featured'] = Request::input('s_status');
        $data['image'] = $image_path;
        $blog->update($data);

        foreach (Request::input('tags') as $item) {
            Tag::updateOrCreate([
                'name' => $item
            ]);
        }

        return Redirect::route('blogs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Blog $blog
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        return response()->json(['msg' => "Comment is deleted..."]);
    }


    public function saveComment(): \Illuminate\Http\JsonResponse
    {
        Comment::create([
            'blog_id' => Request::input('postId'),
            'user_id' => Auth::id(),
            'message' => Request::input('comment')
        ]);
        return response()->json('Coment Created');
    }


}
