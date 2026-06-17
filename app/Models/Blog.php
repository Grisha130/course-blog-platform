<?php

namespace App\Models;

use App\Enums\BlogStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

#[Fillable(['title', 'slug', 'user_id', 'image', 'status', 'content', 'category_id', 'is_active', 'published_at'])]
class Blog extends Model
{
    use SoftDeletes;
    public function user(){
        return $this->belongsTo(User::class);
    }
    #[Override]
    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function blogComments(){
        return $this->hasMany(BlogComment::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function tags(){
        return $this->belongsToMany(Tag::class);
    }
    protected $casts = [
        'status' => BlogStatus::class,
        'published_at' => 'datetime',
        'is_active' => 'boolean',
    ];

}
