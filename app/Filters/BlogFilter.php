<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;


class BlogFilter
{
    public function blogFilter(Builder $query, array $filters)
    {
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $search = str_replace(['%', '_'], ['\%', '\_'], $filters['search']);
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }
        if(!empty($filters['category_id'])){
            $query->where('category_id', $filters['category_id']);
        }
        if(!empty($filters['tag_id'])){
            $query->whereHas('tags', function ($q) use ($filters) {
                $q->where('tags.id', $filters['tag_id']);
            });
        }
        $sort = $filters['sort'] ?? 'latest';
        $query->orderBy('created_at', $sort === 'oldest' ? 'asc' : 'desc');
        return $query;
    }
    
}
