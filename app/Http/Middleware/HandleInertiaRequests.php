<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => Auth::user() ? [
                'user' => [
                    'id' => Auth::id(),
                    'username' => Auth::user()->name,
                    'role' => Auth::user()->role,
                    'photo' => Storage::url(Auth::user()->photo) ?? '/images/avatar.png',
                ]
            ] : null,
            'ADMIN_URL' => env('APP_URL').'/admin',
            'appName' => config('app.name'),
            'curency' => env('CURRENCY'),
            'flash' => [
                'message' => fn () => $request->session()->get('success'),
            ],
        ]);
    }
}
