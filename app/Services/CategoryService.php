<?php

namespace  App\Services;

use App\Models\Category;

class CategoryService
{
    public function index(){
        return Category::all();
    }
    public function store(array $data){
        return Category::create($data);
    }
    public function update(array $data, Category $category){
       $category->update($data);
       return $category;
    }
}