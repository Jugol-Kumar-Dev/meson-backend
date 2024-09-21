<?php


namespace App\Http\Controllers\Api\Frontend\V1;


use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;

class CourseController extends Controller{


    public function getSingleCourse($id): \Illuminate\Http\JsonResponse
    {
        $course = Course::query()
            ->with([
                'chapters:id,course_id,chapter_title,chapter_details,status',
                'chapters.videos:id,name,course_id,chapter_id,status'
            ])
            ->findOrFail($id);

        $course->instructors = User::query()
            ->whereIn('id', collect(json_decode($course->instractors)))
            ->get();

        return response()->json([
            'course' => $course
        ]);
    }


}
