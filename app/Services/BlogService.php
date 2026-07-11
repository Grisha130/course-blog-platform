<?php

namespace App\Services;

use App\Enums\BlogStatus;
use App\Filters\BlogFilter;
use App\Http\Traits\SanitizesInput;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class BlogService
{
    use SanitizesInput;
    public function __construct(
        protected BlogFilter $blog_filter
    ) {}
    public function index(array $filters = [])
    {
        $query =  Blog::where('status', BlogStatus::PUBLISHED->value);
        $query = $this->blog_filter->blogFilter($query, $filters);
        return $query
            ->with(['user', 'blogComments', 'category', 'tags'])
            ->paginate(10)
            ->withQueryString();
    }
    public function store(array $data, ?object $imageFile = null)
    {
        $data = $this->sanitize($data, ['title', 'content']);
        $data['slug'] = Str::slug($data['title'] . '-' . uniqid());
        $data['user_id'] = auth()->user()->id;
        if ($imageFile) {
            $data['image'] = $imageFile->store('blogImage', 'public');
        }
        if (isset($data['status']) && $data['status'] === BlogStatus::PUBLISHED->value) {
            $data['published_at'] = now();
        }
        $blog = Blog::create($data);
        $blog->tags()->attach($data['tags']);
        return $blog->load(['user', 'blogComments', 'category', 'tags']);
    }
    public function update(array $data, Blog $blog, ?object $imageFile = null)
    {
        $data = $this->sanitize($data, ['title', 'content']);
        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title'] . '-' . uniqid());
        }
        if ($imageFile) {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $data['image'] = $imageFile->store('blogImage', 'public');
        }
        if (isset($data['status'])) {
            if ($data['status'] === BlogStatus::PUBLISHED->value) {
                $data['published_at'] = now();
            } else {
                $data['published_at'] = null;
            }
        }
        $blog->update($data);
        if (isset($data['tags'])) {
            $blog->tags()->sync($data['tags']);
        }
        return $blog->load(['user', 'blogComments', 'tags', 'category']);
    }
    public function myBlogs(array $filters = [])
    {
        $query = auth()->user()->blogs()->getQuery();
        $query = $this->blog_filter->blogFilter($query, $filters);
        return $query->with(['user', 'blogComments', 'tags', 'category'])->paginate(10)->withQueryString();
    }
    public function deletedBlogs()
    {
        return auth()->user()->blogs()->onlyTrashed()->with(["user", "blogComments", 'tags', 'category'])->paginate(10);
    }
    public function restore(Blog $blog)
    {
        $blog->restore();
        return $blog->refresh()->load(['user', 'blogComments', 'tags', 'category']);
    }
    public function allDeleted(array $filters = [])
    {
        $query = Blog::onlyTrashed();
        $query = $this->blog_filter->blogFilter($query, $filters);
        return $query
            ->with(['user', 'blogComments', 'category', 'tags'])
            ->paginate(10)
            ->withQueryString();
    }
    public function forceDelete(Blog $blog){
        return $blog->forceDelete();
    }
    public function blocked(array $filters = []){
        $query = Blog::where('is_active', false);
        $query = $this->blog_filter->blogFilter($query, $filters);
        return $query
            ->with(['user', 'blogComments', 'category', 'tags'])
            ->paginate(10)
            ->withQueryString();
    }
    public function block(Blog $blog){
        if($blog->is_active === true){
            $blog->update(['is_active'=>false]);
        }else{
            $blog->update(['is_active'=>true]);
        }
        return $blog->load(['user', 'blogComments', 'category', 'tags']);
    }
}
