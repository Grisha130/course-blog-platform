<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseComment;

class CourseCommentService
{
    public function comment(array $data, Course $course)
    {
        $data['user_id'] = auth()->user()->id;
        $data['course_id'] = $course->id;
        $comment = CourseComment::create($data);
        return $comment->load('user');
    }
    public function updateComment(array $data, CourseComment $comment)
    {
        $comment->update($data);
        return $comment->load('user');
    }
}
