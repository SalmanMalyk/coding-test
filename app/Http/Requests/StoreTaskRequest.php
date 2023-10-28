<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // check if id value is not null than it should be string and max length should be 255 and it should be unique in tasks table
            'id' => ['nullable', 'integer', 'exists:tasks,id'],
            'name' => ['required', 'string', 'max:255'],
            'phase_id' => ['required', 'integer', 'exists:phases,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}
