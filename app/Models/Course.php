<?php

namespace App\Models;

use App\Enums\CourseStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

#[Fillable(['title', 'slug', 'user_id', 'image', 'status', 'description', 'price', 'published_at'])]
class Course extends Model
{
    use SoftDeletes;
    #[Override]
    public function getRouteKeyName()
    {
        return 'slug';
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    protected $casts = [
        'status'=> CourseStatus::class,
        'published_at'=>'datetime',
    ]; 
}
