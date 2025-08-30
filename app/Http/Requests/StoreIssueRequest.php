<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIssueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => ['required', Rule::in(['open', 'in_progress', 'closed'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'due_date' => 'required|date|after_or_equal:today', 
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'assigned_users' => 'nullable|array',
            'assigned_users.*' => 'exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'due_date.required' => 'Due date is required',
            'due_date.after_or_equal' => 'Due date must be today or a future date',
        ];
    }
}