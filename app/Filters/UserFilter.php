<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;


class UserFilter
{
    public function filter(Builder $query, array $filters)
    {
        if (!empty($filters['search'])) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $filters['search']);
            $query->where('email', 'like', '%' . $search . '%');
        }
        if (!empty($filters['status'])) {
            $query->where('is_active', match ($filters['status']) {
                'blocked' => false,
                'active' => true,
                default => true,
            });
        }
        return $query;
    }
}
