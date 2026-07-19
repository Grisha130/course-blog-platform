<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\CommentStoreRequest;
use App\Http\Requests\Blog\CommentUpdateRequest;
use App\Http\Resources\BlogCommentResource;
use App\Http\Traits\ApiResponse;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Services\BlogCommentService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{
    use ApiResponse, AuthorizesRequests;
    public function __construct(
        protected BlogCommentService $blog_comment_service
    ){}
    public function comment(CommentStoreRequest $request, Blog $blog){
         $this->authorize('view', $blog);
        $comment = $this->blog_comment_service->comment($request->validated(), $blog);
        return $this->success(new BlogCommentResource($comment), 'commented successfuly', 201);
    }
    public function update(CommentUpdateRequest $request, BlogComment $comment){
        $this->authorize('update', $comment);
        $comment = $this->blog_comment_service->update($request->validated(), $comment);
        return $this->success(new BlogCommentResource($comment), 'comment updated', 200);
    }
    public function destroy(BlogComment $comment){
        $this->authorize('delete', $comment);

        $comment->delete();
        return $this->success(null, 'deleted successfuly');

    }
}
