<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Course;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        return inertia('Blog/List', [
            'blogs' => Blog::query()->with(['category', 'user'])
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->where('type', '=', 'blog')
                ->paginate(Request::input('perPage') ?? 12)
                ->withQueryString()
                ->through(fn($blog) => [
                    'id' => $blog->id,
                    'title' => Str::limit($blog->title, 50),
                    'description' => Str::limit($blog->description, 80),
                    'cover' => Storage::url($blog->image),
                    'user' => $blog->user,
                    'category' => $blog->category,
                    'created_at' => $blog->created_at->format('F d, Y'),
                    'viewers' => $blog->view_count,
                    'publication_status' => $blog->publication_status,
                    'is_featured' => $blog->is_featured,
                    'show_url' => env('FRONTEND_URL') . '/blog/' . $blog->id,
                    'edit_url' => URL::route('blogs.edit', $blog->id),
                    'delete_url' => URL::route('blogs.destroy', $blog->id),
                    'comment_url' => URL::route('blogs.show', [$blog->slug, 'is_delete' => true])
                ]),
            'filters' => Request::only(['search', 'perPage']),
            'url' => URL::route('blogs.index')
        ]);
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {


        $data = Request::validate([
            'title' => 'required|max:250|min:5',
            'category_id' => 'required',
            'description' => 'nullable|max:1000',
            'content' => 'required',
            'tags' => 'array',
            'p_status' => 'boolean',
            's_status' => 'boolean',
            'cover' => 'required|mimes:jpg,jpeg,png,gif,svg'
        ]);

        $image_path = null;
        if (Request::hasFile('cover')) {
            $image_path = Request::file('cover')->store('image', 'public');
        }

        $data['user_id'] = Auth::id();
        $data['tags'] = json_encode(Request::input('tags'));
        $data['publication_status'] = Request::input('p_status');
        $data['is_featured'] = Request::input('s_status');
        $data['image'] = $image_path;
        $data['type'] = 'blog';

        Blog::create($data);

        if (!empty(Request::input('tags'))) {
            foreach (Request::input('tags') as $item) {
                Tag::updateOrCreate([
                    'name' => $item
                ]);
            }
        }

        return Redirect::route('blogs.index');

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Blog $blog
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function show(Blog $blog)
    {
        return inertia('Course/Show');
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
            'description' => 'nullable|max:1000',
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
        $data['type'] = 'blog';

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
    public function destroy(Blog $blog)
    {
        $blog->delete();
        return back()->with(['msg' => "Comment is deleted..."]);
    }

    public function allComments($slug)
    {
        //
    }


}
