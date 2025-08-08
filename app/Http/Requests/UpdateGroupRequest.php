<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $group = $this->route('group');
        
        // Only group owner or moderators can update group
        return Auth::check() && 
               ($group->isOwner(Auth::user()) || $group->isModerator(Auth::user()));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $group = $this->route('group');
        
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('groups', 'name')->ignore($group->id)
            ],
            'description' => 'required|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'rules' => 'nullable|array',
            'rules.*.title' => 'required_with:rules|string|max:255',
            'rules.*.description' => 'required_with:rules|string|max:500',
            'resources' => 'nullable|array',
            'resources.*.title' => 'required_with:resources|string|max:255',
            'resources.*.url' => 'required_with:resources|url|max:500',
            'is_private' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.unique' => 'A group with this name already exists.',
            'name.required' => 'Group name is required.',
            'description.required' => 'Group description is required.',
            'photo.image' => 'Group photo must be an image.',
            'photo.max' => 'Group photo must not exceed 2MB.',
            'banner.image' => 'Group banner must be an image.',
            'banner.max' => 'Group banner must not exceed 4MB.',
            'rules.*.title.required_with' => 'Rule title is required when adding rules.',
            'rules.*.description.required_with' => 'Rule description is required when adding rules.',
            'resources.*.title.required_with' => 'Resource title is required when adding resources.',
            'resources.*.url.required_with' => 'Resource URL is required when adding resources.',
            'resources.*.url.url' => 'Resource URL must be a valid URL.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_private' => $this->boolean('is_private'),
        ]);
    }
}
