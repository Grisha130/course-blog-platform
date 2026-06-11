<?php

namespace App\Services;

use App\Enums\CourseStatus;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CourseService
{
    public function index(){
        return Course::where('status', CourseStatus::PUBLISHED->value)->get();
    }
    public function myCourses(){
        return auth()->user()->courses;
    }
    public function store(array $data, ?object $imageFile = null) 
    {
        $data['slug'] = Str::slug($data['title'] . '-' . uniqid());
        $data['user_id'] = auth()->user()->id;
        if($imageFile){
            $data['image'] = $imageFile->store('courseImage', 'public');
        }
        if(isset($data['status']) && $data['status'] === CourseStatus::PUBLISHED->value ){
            $data['published_at'] = now();
        }
        $course = Course::create($data);
        return $course->fresh();
    }
    public function update(array $data, Course $course, ?object $imageFile = null){
        if(isset($data['title'])){
            $data['slug'] = Str::slug($data['title'] . '-' . uniqid());
        }
        if($imageFile){
            if($course->image){
                Storage::disk('public')->delete($course->image);
            }
            $data['image'] = $imageFile->store('courseImage', 'public');
        }
        if(isset($data['status']) && $data['status'] === CourseStatus::PUBLISHED->value){
            $data['published_at'] = now();
        }
        $course->update($data);
        return $course;
    }
    public function deletedCourses(){
        return auth()->user()->courses()->onlyTrashed()->with('user')->get();
    }
    public function restore(Course $course){
        $course->restore();
        return $course;
    }
    
}