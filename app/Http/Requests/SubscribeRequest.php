<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscribeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'blog_id' => 'required|exists:blogs,id'
        ];
    }
}
