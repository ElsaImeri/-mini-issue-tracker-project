<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'deadline' => 'nullable|date|after_or_equal:start_date',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required',
            'name.max' => 'Project name must not exceed 255 characters',
            'start_date.date' => 'Start date must be a valid date',
            'deadline.date' => 'Deadline must be a valid date',
            'deadline.after_or_equal' => 'Deadline must be after or equal to start date',
        ];
    }
}