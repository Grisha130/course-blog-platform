<?php

namespace App\Services;

use App\Enums\BlogStatus;
use App\Filters\BlogFilter;
use App\Models\Blog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class BlogService
{
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
}
