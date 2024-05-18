<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|filled|max:255',
            'description' => 'sometimes|string',
            'requirements' => 'sometimes|string',
            'city' => 'sometimes|string|max:255',
            'country' => 'sometimes|string|max:255',
            'min_salary' => 'sometimes|numeric|min:0',
            'max_salary' => 'sometimes|numeric|min:0',
            'min_exp_years' => 'sometimes|integer|min:0',
            'max_exp_years' => 'sometimes|integer|min:0',
            'expires_at' => 'sometimes|date',
            'type' => 'sometimes|string|in:full-time,part-time,contract,freelance',
            'remote_type' => 'sometimes|string|in:on-site,hybrid,remote',
            'experience_level' => 'sometimes|string|in:entry_level,associate,mid-senior,director,executive',
            'status' => 'prohibited',
            'user_id' => 'prohibited',
            'job_post_id' => 'prohibited'
        ];
    }
}
