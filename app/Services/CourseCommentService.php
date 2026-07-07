<?php

namespace App\Services;

use App\Http\Traits\SanitizesInput;
use App\Models\Course;
use App\Models\CourseComment;

class CourseCommentService
{
    use SanitizesInput;
    public function comment(array $data, Course $course)
    {
        $data = $this->sanitize($data, ['comment']);
        $data['user_id'] = auth()->user()->id;
        $data['course_id'] = $course->id;
        $comment = CourseComment::create($data);
        return $comment->load('user');
    }
    public function updateComment(array $data, CourseComment $comment)
    {
        $data = $this->sanitize($data, ['comment']);
        $comment->update($data);
        return $comment->load('user');
    }
}
