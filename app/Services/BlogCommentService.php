<?php

namespace App\Services;

use App\Http\Traits\SanitizesInput;
use App\Models\Blog;
use App\Models\BlogComment;

class BlogCommentService
{
    use SanitizesInput;
    public function comment(array $data, Blog $blog)
    {
        $data = $this->sanitize($data, ['comment']);
        $data['user_id'] = auth()->user()->id;
        $data['blog_id'] = $blog->id;
        $comment = BlogComment::create($data);
        return $comment->load('user');
    }
    public function update(array $data, BlogComment $comment){
        $data = $this->sanitize($data, ['comment']);
        $comment->update($data);
        return $comment->load('user');
    }
}
