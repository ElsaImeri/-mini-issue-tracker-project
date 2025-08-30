<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:tags,name',
            'color' => 'nullable|string|size:7|starts_with:#|regex:/^#[a-f0-9]{6}$/i',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tag name is required',
            'name.unique' => 'A tag with this name already exists',
            'name.max' => 'Tag name must not exceed 255 characters',
            'color.size' => 'Color must be a valid hex code (7 characters)',
            'color.starts_with' => 'Color must start with #',
            'color.regex' => 'Color must be a valid hex color code',
        ];
    }
}