<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $groupId = $this->route('group'); // Assumes 'group' is the route parameter name

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('groups', 'name')->ignore($groupId),
            ],
            'active' => 'required|boolean',
        ];
    }
}
