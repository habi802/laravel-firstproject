<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBlogRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|unique:blogs,name|max:255|min:4',
            'display_name' => 'required|max:255'
        ];
    }
}
