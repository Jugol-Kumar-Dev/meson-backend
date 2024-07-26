<?php

namespace App\Http\Controllers;



use App\Models\MoktestLink;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

class MocktestLinkController extends Controller
{
    public function index(){

        return inertia('ApiMocktest/List', [
            'mocktests' => MoktestLink::query()->latest()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($mock) => [
                    'id' => $mock->id,
                    'title' => $mock->title,
                    'status' => $mock->status,
                    'student_show'=>$mock->student_show,
                    'url'=> $mock->link,
                    'active_on' => $mock->created_at->format('d M Y'),
                    'show_url' => URL::route('courses.show', $mock->id),
                    'edit_url' => URL::route('courses.edit', $mock->id),
                ]),

            'filters' => Request::only(['search','perPage']),
            'url' => URL::route('courses.index')
        ]);
    }

    public function store(){
        MoktestLink::create([
            'status' => Request::input('status'),
            'title' => Request::input('mock_title'),
            'link' => Request::input('mocktest_link')
        ]);
    }


    function publishedForStudent(Request $request, $id){

        $mocktest = MoktestLink::findOrFail($id);
        $mocktest->update([
            'student_show' => Request::input('show_status') == 'true' ? 1 : 0
        ]);
        return back();
    }


    function activation(Request $request, $id){
        $mocktest = MoktestLink::findOrFail($id);
        $mocktest->update([
            'status' => Request::input('show_status') == 'true' ? 1 : 0
        ]);
        return back();
    }



    public function destroy($id)
    {
        $mocktest = MoktestLink::findOrFail($id);
        $mocktest->delete();
        return back();
    }



}
