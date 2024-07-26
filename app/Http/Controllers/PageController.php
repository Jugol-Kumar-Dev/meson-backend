<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Page;
use App\Models\Seo;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {

        return inertia('Pages/Index', [
            'pages' => Page::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString(),

            'filters' => Request::only(['search','perPage']),
            'url' => URL::route('pages.index'),
        ]);
    }

    public function create()
    {
        return inertia('Pages/Create',[
            'url' => URL::route('pages.index'),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        Request::validate([
            'title' => 'required|max:255|unique:pages',
            'content' => 'required'
        ]);

        $page = new Page();
        $page->title = Request::input('title');
        $page->slug = Str::slug(Request::input('title'), '-'); //Request::input('slug');
        $page->summery = Request::input('content');
        $page->status = true;
        $page->is_delete= true;
        $page->save();

        return redirect()->route('pages.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Inertia\Response|\Inertia\ResponseFactory | \Illuminate\Http\JsonResponse
     */
    public function show($slug)
    {
        $page = Page::query()->whereSlug($slug)->first();
        if(\request()->input('page') == 'edit'){
            return inertia('Pages/Edit',[
                'page' => $page,
                'url' => URL::route('page.update', $page->slug),
            ]);
        }else{
            return response()->json($page, 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\RedirectResponse
     */


    public function updatePage($slug){

        $page = Page::query()->whereSlug($slug)->first();
        $page->title = Request::input('title');
        $page->slug = Str::slug(Request::input('title'), '-'); //Request::input('slug');
        $page->summery = Request::input('content');
        $page->status = true;
        $page->is_delete= true;
        $page->save();

        return redirect()->route('pages.index')->with('message', 'Your page Updated...');

    }


    public function update(Request $request, Page $page)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Page $page)
    {
        $page->delete();
        return back();
    }


    public function navPages(){
        $ids = collect(json_decode(get_setting('headerPages')));
        $pages = [];
        if(count($ids) > 0){
            $pages = Page::query()->whereIn('id', $ids)->get();
        }
        return response()->json($pages);
    }


}
