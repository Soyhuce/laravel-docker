<?php

namespace Soyhuce\Docker\DTO;

use Illuminate\Support\Str;

trait FormatKeys
{
    public function formatKeys(array $parameters): array
    {
        return collect($parameters)->flatMap(static function ($value, $key) {
            return [Str::camel($key) => $value];
        })->all();
    }
}
