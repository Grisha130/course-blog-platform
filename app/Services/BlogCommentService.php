<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\BlogComment;

class BlogCommentService
{
    public function comment(array $data, Blog $blog)
    {
        $data['user_id'] = auth()->user()->id;
        $data['blog_id'] = $blog->id;
        $comment = BlogComment::create($data);
        return $comment->load('user');
    }
    public function update(array $data, BlogComment $comment){
        $comment->update($data);
        return $comment->load('user');
    }
}
