<?php

namespace App\Http\Traits;

trait SanitizesInput
{
    /**
     * Strip HTML/script tags from the given fields in a data array.
     *
     * @param array $data
     * @param array $fields
     * @return array
     */
    protected function sanitize(array $data, array $fields): array
    {
        foreach ($fields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = strip_tags($data[$field]);
            }
        }
        return $data;
    }
}