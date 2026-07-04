<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Http\Traits\ApiResponse;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse, AuthorizesRequests;
    public function __construct(
        protected CategoryService $category_service
    ){}
    public function index(){
        $categories = $this->category_service->index();
        return $this->success($categories, 'all categories');
    }
    public function store(CategoryStoreRequest $request){
        $this->authorize('create', Category::class);
        $category = $this->category_service->store($request->validated());
        return $this->success($category, 'category created', 201);
    }
    public function update(CategoryUpdateRequest $request ,Category $category){
        $this->authorize('update', $category);
        $category = $this->category_service->update($request->validated(), $category);
        return $this->success($category, 'category updated');
    }
    public function destroy(Category $category){
        $this->authorize('update', $category);
        $category->delete();
        return $this->success(null, 'category deleted');
    }
}
