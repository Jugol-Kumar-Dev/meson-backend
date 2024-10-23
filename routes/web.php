<?php

use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LiveClassCOntroller;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BusinessSettingController;
use App\Http\Controllers\PayPalPaymentController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\QuestinCategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\StudentChatController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SuportController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\MocktestLinkController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MocktestController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ChildCategoryController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', fn()=> Inertia::render('Auth/Login'))->name('master');

Route::middleware('guest')->group(function (){
    Route::get('/login', fn()=> Inertia::render('Auth/Login'));
    Route::post('student/register', [RegisteredUserController::class, 'store'])->name('createStudnet');
    Route::get('/forgot/password', fn() => Inertia::render('Auth/ResetPassword'));
    Route::post('/update/new-password', [NewPasswordController::class, 'store'])->name('updateNewPassword');
});




Route::middleware(['auth'])->group(function () {
    Route::get('/active-account', fn() => view('active-acount'));


    Route::prefix('panel')->middleware('admin_instructor')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin']);
        Route::resource('categories', CategoryController::class);
        Route::resource('sub_categories', SubCategoryController::class);
        Route::resource('child_categories', ChildCategoryController::class);
        Route::resource('courses', CourseController::class);
        Route::put('course/save-mocktst/{id}', [CourseController::class, 'saveMocktst'])->name('save_mocktest');
        Route::post('course/add-meetings', [CourseController::class, 'courseZoomStore'])->name('add_course_mitting');
        Route::delete('meetings/course/delete-meetings/{id}', [CourseController::class, 'deleteZoomFormCourse'])->name('deleteZoomCourse');

        Route::resource('lessons', LessonController::class);
        Route::post('lesson/activation/{id}', [LessonController::class, 'activation']);
        Route::post('lessons/{id}/update', [LessonController::class, 'updateLesson']);

        Route::resource('promos', PromoController::class);
        Route::resource('sliders', SliderController::class);


        Route::resource('chapter', ChapterController::class);

        Route::get('questions-categories', [QuestinCategoryController::class, 'index'])->name('questinoCategoryIndex');
        Route::post('questions-categories', [QuestinCategoryController::class, 'store'])->name('questinoCategoryStore');
        Route::post('questions-categories-update/{id}', [QuestinCategoryController::class, 'update'])->name('questinoCategoryUpdate');
        Route::delete('questions-categories/{id}', [QuestinCategoryController::class, 'delete'])->name('questinoCategoryDelete');


        Route::resource('questions', QuestionController::class);



        Route::resource('mocktests', MocktestController::class);
        Route::get('mock-full-details/{id}', [MocktestController::class, 'mockFullDetails']);
        Route::post('mocktsts/published-for-student/{id}', [MocktestController::class, 'publishedForStudent'])->name('published_mocktest');
        Route::post('mocktsts/activation/{id}', [MocktestController::class, 'activation']);
        Route::delete('mocktests/mocktests/delete-meetings', [CourseController::class, 'deleteMockFormCourse'])->name('deleteMockFormCourse');

        // get all questions for create mocktest
        Route::get('questions-for-mock-create', [MocktestController::class, 'getAllQuestionsForMocktests']);
        Route::get('mocktest/student-result', [MocktestController::class, 'getMocktestStudentResult']);





        Route::resource('api-mocktest', MocktestLinkController::class);
    //        Route::delete('api-mocktest/{id}', [MocktestLinkController::class, 'destroy']);
        Route::post('mocktsts-link/published-for-student/{value}', [MocktestLinkController::class, 'publishedForStudent']);
        Route::post('mocktsts-link/activation/{value}', [MocktestLinkController::class, 'activation']);


        Route::resource('transactions', TransactionController::class);
        Route::get('/show-transactions/{trx}', [TransactionController::class, 'show']);

        Route::resource('subscriptions', SubscriptionController::class);
        Route::resource('live-class', LiveClassController::class);
        Route::resource('meetings', ZoomController::class);

        Route::get('students', [UserController::class, 'student'])->name('student.list');
        Route::get('students/{id}', [UserController::class, 'showStudent'])->name('student.show');
        Route::get('student/edit/{id}', [UserController::class, 'showSingleStudent'])->name('student.edit');
        Route::post('student/store', [UserController::class, 'storeStudent'])->name('student.store');
        Route::delete('student/delete/{id}', [UserController::class, 'destroy'])->name('student.delete');
        Route::post('student/activation/{id}', [UserController::class, 'activation']);
        Route::post('student/{id}', [UserController::class, 'updateStudent']);//->name('student.update');
        Route::post('student/update/{id}', [UserController::class, 'updateStudent'])->name('student.update');

        Route::post('student/course-stratus/{id}', [UserController::class, 'assignCourseActivationStatus']);
        Route::delete('student/course-delete/{id}', [UserController::class, 'deleteAssignCourse']);
        Route::get('student/course-details/{id}', [UserController::class, 'showAssignCourse']);
        Route::put('student/course-details-update/{id}', [UserController::class, 'updateAssignCourse']);


        Route::get('instructors', [UserController::class, 'instructor'])->name('instructor.list');
        Route::delete('instructors/{id}', [UserController::class, 'destroy']);
        Route::get('admins', [UserController::class, 'admin'])->name('admin.list');
        Route::post('admins', [UserController::class, 'store'])->name('admin.store');

        Route::resource('coupons', CouponController::class);
        Route::post('coupon/activation/{id}', [CouponController::class, 'activation'])->name('coupon_activation');

        Route::resource('blogs', BlogController::class);
        Route::post('blogs/update/{id}', [BlogController::class, 'updateBlog'])->name('blogs.update_blog');
        Route::get('blogs/comments/{slug}', [BlogController::class, 'allComments'])->name('blog.comments');
        Route::get('blogs/comments/delete/{comment_id}', [CommentController::class, 'deleteBlogComment'])->name('delete.blog_comment');

        Route::post('/reset-password', [AdminController::class, 'resetPassword']);

        Route::get('profile', [UserController::class, 'profile'])->name('admin_profile');
        Route::post('profile', [UserController::class, 'profile_update']);
        Route::get('setting', [UserController::class, 'setting'])->name('setting');
        Route::post('setting', [UserController::class, 'setting_update']);
    //    Route::get('dashboard', [DashboardController::class, 'instructor'])->name('instructor.dashboard');

        Route::resource('faqs', FaqController::class)->only(['index', 'store', 'destroy']);

        Route::post('pages/update-page/{slug}', [PageController::class, 'updatePage'])->name('page.update');
        Route::resource('pages', PageController::class);

        Route::resource('review', ReviewController::class);

        Route::resource('groups', GroupController::class);
        Route::get('/my-posts', [GroupController::class, 'myPosts']);
        Route::get('/pending-posts', [GroupController::class, 'pendingPosts']);
        Route::post('groups/save-comment/{postId}', [GroupController::class, 'saveComment']);
        Route::delete('groups/delete-comments/{comment_id}', [CommentController::class, 'deleteBlogComment'])->name('delete.blog_comment');

        Route::post('/update-status/{id}', [GroupController::class, 'updateStatus']);
        Route::get('/update-comment-status/{id}', [GroupController::class, 'updateCommentStatus']);


        Route::get('/chats', [ChatController::class, 'index'])->name('chats');
        Route::get('chat/users', [ChatController::class, 'users'])->name('chat.users');//->middleware('auth');;
        Route::get('chat/users/{id}', [ChatController::class, 'user'])->name('users');//->middleware('auth');;
        Route::post('chat/chats', [ChatController::class, 'sendMessage'])->name('send');

        Route::resource('/support',SuportController::class);


        Route::get('/settings', [BusinessSettingController::class, 'index']);
        Route::post('/settings-update', [BusinessSettingController::class, 'updateSetting']);

    });


/*    Route::middleware(['is_student','is_verified'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'student'])->name('dashboard');
        Route::resource('wishlists', WishlistController::class);
        Route::get('purchase', [StudentController::class, 'purchase_history'])->name('purchase_history');
        Route::get('courses', [StudentController::class, 'course_list'])->name('course_list');
        Route::get('courses/{slug}', [StudentController::class, 'course_details'])->name('course_details');
        Route::get('mocktest', [StudentController::class, 'mocktest_list'])->name('mocktest_list');
        Route::get('mocktest/{id}', [StudentController::class, 'mocktest_details'])->name('mocktest_details');
        Route::get('mocktest-start/{id}', [StudentController::class, 'mocktest_enroll'])->name('mocktest_enroll');
        Route::post('mocktest', [StudentController::class, 'mocktest_enroll_store'])->name('mocktest_enroll_store');
        Route::get('mocktest/single-result/{givenid}', [StudentController::class, 'showResult'])->name('single_mock_result');
        Route::get('/mocktest-result/{id}', [StudentController::class, 'mocktestResults']);

        Route::get('student/singele-mock', [MocktestController::class, 'singleMocktest']);

        Route::get('profile', [StudentController::class, 'student_profile']);
        Route::post('profile', [StudentController::class, 'student_profile_update']);

        Route::post('/reset-password', [AdminController::class, 'resetPassword']);

        Route::get('/student/chats', [StudentChatController::class, 'index'])->name('student.chats');


    });*/
});

Route::post('/update-cat/{id}', [CategoryController::class, 'updatecheck']);
Route::post('/update-courses/{id}', [CourseController::class, 'updateCourses']);
Route::get('/update-course-status/{status}/{id}', [CourseController::class, 'updateCourseStatus']);



Route::get('pay/paypal', [PayPalPaymentController::class, 'charge'])->middleware('auth')->name('paypal.pay');
Route::get('pay/success', [PayPalPaymentController::class, 'success'])->name('paypal.success');
Route::get('pay/error', [PayPalPaymentController::class, 'error'])->name('paypal.error');

Route::get('/storage', function (){
   \Illuminate\Support\Facades\Artisan::call('storage:link');
   dd("linked");
});


require __DIR__.'/auth.php';
