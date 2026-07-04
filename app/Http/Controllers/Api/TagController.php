<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\TagRequest;
use App\Http\Traits\ApiResponse;
use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TagController extends Controller
{
    use ApiResponse, AuthorizesRequests;
    public function __construct(
        protected TagService $tag_service
    ){}
    public function index(){
        $tags = $this->tag_service->index();
        return $this->success($tags, 'all tags');
    }
    public function store(TagRequest $request){
        $this->authorize('create',Tag::class);
        $tag = $this->tag_service->store($request->validated());
        return $this->success($tag, 'tag created', 201);
    }
    public function update(TagRequest $request, Tag $tag){
        $this->authorize('update',Tag::class);
        $tag = $this->tag_service->update($request->validated(), $tag);
        return $this->success($tag, 'tag updated');
    }
    public function destroy(Tag $tag){
        $this->authorize('delete',Tag::class);
        $tag->delete();
        return $this->success(null, 'tag deleted');
    }
}
