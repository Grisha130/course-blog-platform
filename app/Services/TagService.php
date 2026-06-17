<?php

namespace App\Services;

use App\Models\Tag;

class TagService
{
    public function index(){
        return Tag::all();
    }
    public function store(array $data){
        return Tag::create($data);
    }
    public function update(array $data, Tag $tag){
        $tag->update($data);
        return $tag;
    }
}