<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlayhouseFormRequest extends FormRequest
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
            'phone' => 'required|string|max:20',
            'parentName' => 'required|string|max:255',
            'parentLastName' => 'required|string|max:255',
            'parentEmail' => 'nullable|email|max:255',
            'mkt_code' => 'nullable|string|max:50',
            'parentBirthday' => 'required|date',
            'child' => 'required|array|min:1',
            'child.*.name' => 'required|string|max:255',
            'child.*.birthday' => 'required|date',
            'child.*.playDuration' => 'required|string|max:50',
            'child.*.phoneNumber' => 'nullable|string|max:20',
            'child.*.addSocks' => 'nullable|boolean',
            'child.*.photo' => 'nullable|string',
            'child.*.guardianName' => 'nullable|string|max:255',
            'child.*.guardianLastName' => 'nullable|string|max:255',
            'child.*.guardianPhone' => 'nullable|string|max:20',
            'child.*.guardianBirthday' => 'nullable|date',
            'child.*.guardianAuthorized' => 'nullable|boolean',
            'discountCode' => 'nullable|string|max:50',
            'fb_pp_url' => 'nullable|url|max:255',
            'isfollowedFb' => 'nullable|boolean'
        ];
    }
}
