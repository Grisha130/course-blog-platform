<?php

namespace App\Services;

use App\Enums\CourseStatus;
use App\Filters\CourseFilter;
use App\Http\Resources\UserResource;
use App\Http\Traits\SanitizesInput;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CourseService
{
    use SanitizesInput;
    public function __construct(
        protected CourseFilter $filter
    ) {}
    public function index(array $filters = [])
    {
        $query = Course::where('status', CourseStatus::PUBLISHED->value);
        $query = $this->filter->courseFilter($query, $filters);
        return $query
            ->with(['user', 'comments.user'])
            ->paginate(10)
            ->withQueryString();
    }

    public function myCourses(array $filters = [])
    {

        $query = auth()->user()->courses()->getQuery();
        $query = $this->filter->myCourseFilter($query, $filters);
        return $query
            ->with(['user', 'comments.user'])
            ->paginate(10)
            ->withQueryString();
    }
    public function store(array $data, ?object $imageFile = null)
    {
        $data = $this->sanitize($data, ['title', 'description']);
        $data['slug'] = Str::slug($data['title'] . '-' . uniqid());
        $data['user_id'] = auth()->user()->id;
        if ($imageFile) {
            $data['image'] = $imageFile->store('courseImage', 'public');
        }
        if (isset($data['status']) && $data['status'] === CourseStatus::PUBLISHED->value) {
            $data['published_at'] = now();
        }
        $course = Course::create($data);
        return $course->load(['user', 'comments.user']);
    }
    public function update(array $data, Course $course, ?object $imageFile = null)
    {
        $data = $this->sanitize($data, ['title', 'description']);
        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title'] . '-' . uniqid());
        }
        if ($imageFile) {
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }
            $data['image'] = $imageFile->store('courseImage', 'public');
        }
        if (isset($data['status'])) {
            if ($data['status'] === CourseStatus::PUBLISHED->value) {
                $data['published_at'] = now();
            } else {
                $data['published_at'] = null;
            }
        }

        $course->update($data);
        return $course->load(['user', 'comments.user']);
    }
    public function deletedCourses()
    {
        return auth()->user()
            ->courses()
            ->onlyTrashed()
            ->with(['user', 'comments.user'])
            ->paginate(10);
    }
    public function restore(Course $course)
    {
        $course->restore();
        return $course->refresh()->load(['user', 'comments.user']);
    }
    public function archived()
    {
        $user = auth()->user();
        return $user->courses()
            ->where('status', CourseStatus::ARCHIVED->value)
            ->with(['user', 'comments.user'])
            ->paginate(10);
    }
    public function allDeleted(array $filters = [])
    {
        $query = Course::onlyTrashed();
        $query = $this->filter->courseFilter($query, $filters);
        return $query
            ->with(['user', 'comments.user'])
            ->paginate(10)
            ->withQueryString();
    }
    public function forceDelete(Course $course)
    {
        return  $course->forceDelete();
    }
}
