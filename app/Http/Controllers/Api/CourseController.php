<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseCommentRequest;
use App\Http\Requests\Course\FilterRequest;
use App\Http\Requests\Course\MyCourseFilterRequest;
use App\Http\Requests\Course\StoreRequest;
use App\Http\Requests\Course\UpdateRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponse;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CourseController extends Controller
{
    use ApiResponse, AuthorizesRequests;
    public function __construct(
        protected CourseService $course_service
    ) {}
    public function index(FilterRequest $request)
    {
        $courses = $this->course_service->index($request->validated());
        return $this->paginate(
            CourseResource::collection($courses),
            'all courses with pagination and filter',
            200
        );
    }
    public function myCourses(MyCourseFilterRequest $filter)
    {
        $courses = $this->course_service->myCourses($filter->validated());
        return $this->paginate(
            CourseResource::collection($courses),
            'my courses',
            200
        );
    }
    public function showOne(Course $course)
    {
        $this->authorize('view', $course);
        $course->load(['user', 'comments.user']);
        return $this->success(
            new CourseResource($course),
            'one course',
            200
        );
    }
    public function store(StoreRequest $request)
    {
        $course = $this->course_service->store($request->validated(), $request->file('image'));
        return $this->success(new CourseResource($course), 'created successfully', 201);
    }
    public function update(UpdateRequest $request, Course $course)
    {
        $this->authorize('update', $course);
        $course = $this->course_service->update($request->validated(), $course, $request->file('image'));
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
    public function deletedCourses()
    {
        $courses = $this->course_service->deletedCourses();
        return $this->paginate(
            CourseResource::collection($courses),
            'deleted courses',
            200
        );
    }
    public function restore(Course $course)
    {
        $this->authorize('restore', $course);
        $restoredCourse = $this->course_service->restore($course);
        return $this->success(
            new CourseResource($restoredCourse),
            'restored'
        );
    }
    public function allDeleted(FilterRequest $request)
    {
        $this->authorize('allDeleted', Course::class);
        $courses = $this->course_service->allDeleted($request->validated());
        return $this->paginate(CourseResource::collection($courses), 'all deleted');
    }
    public function forceDelete(Course $course)
    {
        $this->authorize('forceDelete', $course);
        $course = $this->course_service->forceDelete($course);
        return $this->success(null, 'force deleted course');
    }
    public function blocked(FilterRequest $request)
    {
        $this->authorize('viewBlock', Course::class);
        $courses = $this->course_service->blocked($request->validated());
        return $this->paginate(CourseResource::collection($courses), 'all blocked courses');
    }
    public function block(Course $course)
    {
        $this->authorize('block', $course);
        $course = $this->course_service->block($course);
        return $this->success(new CourseResource($course), 'have done');
    }
    public function adminIndex(MyCourseFilterRequest $request)
    {
        $this->authorize('viewAll', Course::class);
        $courses = $this->course_service->adminIndex($request->validated());
        return $this->paginate(CourseResource::collection($courses), 'all courses (admin)');
    }
}
