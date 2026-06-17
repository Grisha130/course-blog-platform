<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['comment', 'user_id', 'blog_id'])]

class BlogComment extends Model
{
    use SoftDeletes;

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function blog(){
        return $this->belongsTo(Blog::class);
    }
}
