<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobPostRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'min_salary' => 'sometimes|numeric|min:0',
            'max_salary' => 'sometimes|numeric|min:0',
            'min_exp_years' => 'sometimes|integer|min:0',
            'max_exp_years' => 'sometimes|integer|min:0',
            'expires_at' => 'sometimes|date',
            'type' => 'required|string|in:full-time,part-time,contract,freelance',
            'remote_type' => 'required|string|in:on-site,hybrid,remote',
            'experience_level' => 'required|string|in:entry_level,associate,mid-senior,director,executive',
            'status' => 'prohibited',
            'user_id' => 'prohibited',
            'job_post_id' => 'prohibited'
        ];
    }
}
