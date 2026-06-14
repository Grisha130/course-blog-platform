<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class CourseFilter
{
    public function courseFilter(Builder $query, array $filters)
    {
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $search = str_replace(['%', '_'], ['\%', '\_'], $filters['search']);
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if (!empty($filters['type'])) {
            if ($filters['type'] === 'free') {
                $query->where('price', 0);
            } elseif ($filters['type'] === 'paid') {
                $query->where('price', '>', 0);
            }
        }

        $sort = $filters['sort'] ?? 'latest';
        $query->orderBy('created_at', $sort === 'oldest' ? 'asc' : 'desc');
        return $query;
    }
    public function myCourseFilter(Builder $query, array $filters)
    {
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if(!empty($filters['search'])){
            $query->where(function ($q) use ($filters){
            $search = str_replace(['%', '_'], ['\%', '\_'], $filters['search']);
                $q->where('title', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        return $query;
    }
}
