<?php

namespace App\Castables;

use Illuminate\Contracts\Database\Eloquent\Castable;
use App\Casts\Link as LinkCast;

class Link implements Castable
{
    public function __construct(public readonly string $path) {}

    public static function castUsing(array $arguments)
    {
        return LinkCast::class;
    }
}