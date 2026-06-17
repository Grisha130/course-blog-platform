<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Blog\BlogFilterRequest;
use App\Http\Requests\Blog\StoreRequest;
use App\Http\Requests\Blog\UpdateRequest;
use App\Http\Resources\BlogResource;
use App\Http\Traits\ApiResponse;
use App\Models\Blog;
use App\Services\BlogService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use ApiResponse, AuthorizesRequests;
    public function __construct(
        protected BlogService $blog_service
    ) {}
    public function index(BlogFilterRequest $request)
    {
        $blogs = $this->blog_service->index($request->validated());
        return $this->paginate(BlogResource::collection($blogs), 'all published blogs');
    }
    public function store(StoreRequest $request)
    {
        
        $blog = $this->blog_service->store($request->validated(), $request->file('image'));
        return $this->success(
            new BlogResource($blog),
            'blog created',
            201
        );
    }
    public function update(UpdateRequest $request, Blog $blog)
    {
        $this->authorize('update', $blog);
        $blog = $this->blog_service->update($request->validated(), $blog, $request->file('image'));
        return $this->success(new BlogResource($blog), 'blog updated');
    }
    public function destroy(Blog $blog)
    {
        $this->authorize('delete', $blog);
        $blog->delete();
        return $this->success(null, 'blog deleted');
    }
    public function myBlogs(BlogFilterRequest $request){
        $blogs = $this->blog_service->myBlogs($request->validated());
        return $this->paginate(BlogResource::collection($blogs), 'my blogs');
    }
    public function deletedBlogs(){
        $blogs = $this->blog_service->deletedBlogs();
        return $this->paginate(BlogResource::collection($blogs), 'my deleted blogs');
    }
    public function restore(Blog $blog){
        $this->authorize('restore', $blog);
        $blog = $this->blog_service->restore($blog);
        return $this->success(new BlogResource($blog), 'blog restored');
    }
    public function showOne(Blog $blog){
        $blog->load(['user', 'blogComments', 'category', 'tags']);
        return $this->success(new BlogResource($blog), 'one blog0');
    }
}
