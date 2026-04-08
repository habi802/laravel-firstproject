<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
//use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
// use App\Casts\Link;
use App\Castables\Link;
use Illuminate\Database\Eloquent\Prunable;

class Attachment extends Model
{
    use HasFactory, Prunable;

    protected $fillable = [
        'original_name',
        'name'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // public function external()
    // {
    //     return Attribute::make(
    //         get: fn () => preg_match('/^https?/', $this->name)
    //     );
    // }

    // public function link()
    // {
    //     return Attribute::make(
    //         get: function ($value) {
    //             $path = $this->external
    //                   ? $this->name
    //                   : Storage::disk('public')->url($this->name);

    //             return $value ?? $path;
    //         },
    //         set: fn ($value) => $value
    //     );
    // }

    protected $casts = [
        'link' => Link::class
    ];

    public function prunable()
    {
        return static::whereNull('post_id');
    }

    public function pruning()
    {
        Storage::disk('public')->delete($this->name);
    }
}
