<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreRequest;
use App\Http\Requests\Course\UpdateRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponse;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use ApiResponse, AuthorizesRequests;
    public function __construct(
        protected CourseService $course_service
    ) {}
    public function index()
    {
        $courses = $this->course_service->index();
        return $this->success(
            CourseResource::collection($courses),
            'all courses',
            200
        );
    }
    public function myCourses(){
        $courses = $this->course_service->myCourses();
        return $this->success(CourseResource::collection($courses), 'my courses', 200);
    }
    public function showOne(Course $course)
    {
        return $this->success(
            new CourseResource($course),
            'one course',
            200
        );
    }
    public function store(StoreRequest $request)
    {
        $course = $this->course_service->store($request->validated(), $request->file('image'));
        $course->load('user');
        return $this->success(new CourseResource($course), 'created successfully', 201);
    }
    public function update(Course $course, UpdateRequest $request)
    {
        $this->authorize('update', $course);
        $course = $this->course_service->update($request->validated(), $course, $request->file('image'));
        $course->load('user');
        return $this->success(new CourseResource($course), 'course updated', 200);
    }
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        $course->delete();
        return $this->success(
            null,
            'course deleted',
            200
        );
    }
    public function deletedCourses(){
        $courses = $this->course_service->deletedCourses();
        return $this->success(CourseResource::collection($courses), 'deleted courses', 200);
    }
    public function restore(Course $course){
        $this->authorize('restore', $course);
        $restoredCourse = $this->course_service->restore($course);
        $restoredCourse->load('user');
        return $this->success(new CourseResource($restoredCourse), 'restored');
    }
}
