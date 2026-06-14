<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CommentUpdateRequest;
use App\Http\Requests\Course\CourseCommentRequest;
use App\Http\Resources\CommentResource;
use App\Http\Traits\ApiResponse;
use App\Models\Course;
use App\Models\CourseComment;
use App\Services\CourseCommentService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CourseCommentController extends Controller
{
    use ApiResponse, AuthorizesRequests;
    public function __construct(
        protected CourseCommentService $comment_service
    ) {}
    public function comment(CourseCommentRequest $request, Course $course)
    {
        $comment = $this->comment_service->comment($request->validated(), $course);
        return $this->success(new CommentResource($comment), 'commented', 201);
    }
    public function updateComment(CommentUpdateRequest $request, CourseComment $comment)
    {
        $this->authorize('update', $comment);
        $comment = $this->comment_service->updateComment($request->validated(),  $comment);
        return $this->success(new CommentResource($comment), 'comment updated');
    }
    public function destroy(CourseComment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return $this->success(null, 'message deleted successfuly');
    }
}
