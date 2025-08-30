<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'issue_id' => 'required|exists:issues,id',
            'author_name' => 'required|string|max:255',
            'body' => 'required|string|min:1|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'issue_id.required' => 'Issue is required',
            'issue_id.exists' => 'Selected issue does not exist',
            'author_name.required' => 'Author name is required',
            'author_name.max' => 'Author name must not exceed 255 characters',
            'body.required' => 'Comment body is required',
            'body.min' => 'Comment must be at least 1 character long',
            'body.max' => 'Comment must not exceed 2000 characters',
        ];
    }
}