<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\QuestionCategory;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class QuestinCategoryController extends Controller
{
    public function index()
    {

        return inertia('Question/Category', [
            'categories' => QuestionCategory::query()
                ->when(Request::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->paginate(Request::input('perPage') ?? 10)
                ->withQueryString()
                ->through(fn($category) => [
                    'id' => $category->id,
                    'name' => $category->name,
//                    'edit_url' => URL::route('categories.edit', $category->id)
                ]),

            'filters' => Request::only(['search','perPage']),
            'url' => URL::route('questinoCategoryIndex'),
        ]);
    }

    public function store(): \Illuminate\Http\RedirectResponse
    {
        QuestionCategory::create(Request::validate([
            'name' => 'required|max:50',
        ]));
        return Redirect::route('questinoCategoryIndex');
    }

    public function update($id)
    {
        QuestionCategory::findOrFail($id)->update(Request::validate([
            'name' => 'required|max:50',
        ]));
        return Redirect::route('questinoCategoryIndex');
    }
    public function delete($id)
    {
        QuestionCategory::findOrFail($id)->delete();
        return Redirect::route('questinoCategoryIndex');
    }
}
