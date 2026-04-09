<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Castables\Link as LinkCastable;
use Exception;

class Link implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $path = $this->external($attributes['name'])
              ? $attributes['name']
              : Storage::disk('public')->url($attributes['name']);

        //return $value ?? $path;
        return new LinkCastable($path);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        //return $value;
        if (!$value instanceof LinkCastable) {
            throw new Exception('The given value is not an Link instance.');
        }

        return [
            'name' => $value->path
        ];
    }

    private function external(string $name)
    {
        return preg_match('/^https?/', $name);
    }
}
