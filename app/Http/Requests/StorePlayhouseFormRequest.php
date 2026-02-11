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
            'parentName' => 'required|string|max:255',
            'parentLastName' => 'required|string|max:255',
            'parentEmail' => 'required|email|max:255',
            'parentBirthday' => 'nullable|date',
            'phone' => 'required|string|max:20',
            'child' => 'required|array|min:1',
            'child.*.name' => 'required|string|max:255',
            'child.*.birthday' => 'nullable|date',
            'child.*.playDuration' => 'required',
        ];
    }
}
