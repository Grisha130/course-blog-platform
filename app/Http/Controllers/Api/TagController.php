<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\TagRequest;
use App\Http\Traits\ApiResponse;
use App\Models\Tag;
use App\Services\TagService;

class TagController extends Controller
{
    use ApiResponse;
    public function __construct(
        protected TagService $tag_service
    ){}
    public function index(){
        $tags = $this->tag_service->index();
        return $this->success($tags, 'all tags');
    }
    public function store(TagRequest $request){
        $tag = $this->tag_service->store($request->validated());
        return $this->success($tag, 'tag created', 201);
    }
    public function update(TagRequest $request, Tag $tag){
        $tag = $this->tag_service->update($request->validated(), $tag);
        return $this->success($tag, 'tag updated');
    }
    public function destroy(Tag $tag){
        $tag->delete();
        return $this->success(null, 'tag deleted');
    }
}
