<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|numeric|exists:comments,id',
            'content' => 'required|string'
        ];
    }
}
