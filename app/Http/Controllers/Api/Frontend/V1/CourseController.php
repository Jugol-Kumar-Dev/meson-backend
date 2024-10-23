<?php


namespace App\Http\Controllers\Api\Frontend\V1;


use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller{


    public function getSingleCourse($id)
    {

        $course = Course::query()
            ->with([
                'chapters:id,course_id,chapter_title,chapter_details,status',
                'chapters.videos:id,name,course_id,chapter_id,status'
            ])
            ->withCount('orders')
            ->findOrFail($id);

        if($course->instractors && is_array(json_decode($course->instractors))){
            $course->instractors = $course->instractors ? User::query()->select(['name', 'photo', 'about'])
                ->whereIn('id', json_decode($course->instractors))->get() : null;
        }
        $course->inclues = $course->inclues ? json_decode($course->inclues) : NULL;
        $course->features = $course->features ? json_decode($course->features) : NULL;
        $course->faqs = $course->faqs ? json_decode($course->faqs) : NULL;
        return response()->json([
            'course' => $course
        ]);
    }


}
