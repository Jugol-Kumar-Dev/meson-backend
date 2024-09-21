<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


use App\Http\Controllers\Api\Frontend\V1\CourseController;
use App\Http\Controllers\Api\Frontend\V1\HomeController;
use App\Http\Controllers\Api\Frontend\V1\OrderController;
use App\Http\Controllers\Api\Frontend\V1\SSLComesarzController;
use App\Http\Controllers\Api\Student\V1\LoginController;
use App\Http\Controllers\Api\Student\V1\CourseController as StudentCourseController;
use \App\Http\Controllers\Api\Student\V1\MocktestController as StudentMocktestController;
use \App\Http\Controllers\Api\Student\V1\PracticeMocktestController as StudentPracticeMocktestController;
use \App\Http\Controllers\Api\Student\V1\LiveClassController as StudentLiveClassController;
use \App\Http\Controllers\Api\Student\V1\BlogController as StudentBlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// frontend global routes
Route::prefix('frontend')->group(function (){
    Route::controller(HomeController::class)->group(function (){
        // home page settings data
        Route::get('/hero-categories', 'heroCategories');
        Route::get('/hero-courses', 'heroCourses');
        Route::get('/top-for-categories', 'topForCats');
        Route::get('/home-page-courses', 'homeCoures');
        Route::get('/home-second-section', 'secondHomeSection');
        Route::get('/home-blogs','homeBlogs');

        Route::get('/get-settings', 'getSettings');
    });

    Route::controller(CourseController::class)->group(function (){
        Route::get('/get-single-course/{id}', 'getSingleCourse');
    });
});



// student guest rotues
Route::prefix('student')->group(function (){
   Route::post('/login', [LoginController::class, 'login']);
   Route::post('/register', [LoginController::class, 'signup']);
});

// student auth routes
Route::middleware('auth:sanctum')->prefix('student')->group(function (){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // course related routes
    Route::get('/get-my-courses', [StudentCourseController::class, 'myCourses']);
    Route::get('/get-course-lessons/{id}', [StudentCourseController::class, 'courseContent']);
    Route::get('/get-course-details/{id}', [StudentCourseController::class, 'courseDetails']);


    // live exam related routes
    Route::get('/get-live-exams', [StudentMocktestController::class, 'myMocktests']);
    Route::get('/get-given-live-exams', [StudentMocktestController::class, 'getGivenLiveMocktests']);
    Route::get('/get-live-exam-details/{id}', [StudentMocktestController::class, 'getMocktestDetails']);
    Route::post('/check-live-mocktest/{id}', [StudentMocktestController::class, 'checkMocktest']);
    Route::post('/mocktest-live-enrolled/{id}', [StudentMocktestController::class, 'mocktestEnroll']);

    // pracite exam related routes
    Route::get('/get-exam-details/{id}', [StudentPracticeMocktestController::class, 'getMocktestDetails']);
    Route::get('/get-practice-exams', [StudentPracticeMocktestController::class, 'getPracticeExams']);
    Route::post('/check-mocktest/{id}', [StudentPracticeMocktestController::class, 'checkMocktest']);
    Route::post('/mocktest-enrolled/{id}', [StudentPracticeMocktestController::class, 'mockQuestions']);
    Route::post('/finish-mocktest', [StudentPracticeMocktestController::class, 'finishMocktest']);
    Route::get('/show-results/{id}', [StudentPracticeMocktestController::class, 'showResults']);
    Route::get('/show-result-details/{id}', [StudentPracticeMocktestController::class, 'showResultDetails']);

    // create order and payment
    Route::post('/create-order', [OrderController::class, 'store']);
    Route::get('/get-confirm-order/{id}', [OrderController::class, 'getConfirmOrder']);


    // group discation routes
    Route::get('/group-posts', [StudentBlogController::class, 'index']);
    Route::post('/create-post', [StudentBlogController::class, 'store']);
    Route::post('/group-posts/delete/{id}', [StudentBlogController::class, 'destroy']);
    Route::post('/create-post-comment', [StudentBlogController::class, 'saveComment']);

    // live class related routes
    Route::get('/all-live-classes', [StudentLiveClassController::class, 'index']);


    // student logout route
    Route::any('/logout', [LoginController::class, 'logout']);
});

// ssl comesarz payment urls
Route::post('/success', [SSLComesarzController::class, 'success']);
Route::post('/fail', [SSLComesarzController::class, 'fail']);
Route::post('/cancel', [SSLComesarzController::class, 'cancel']);
