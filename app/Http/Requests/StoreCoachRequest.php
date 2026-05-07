<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCoachRequest extends FormRequest
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
        $mode = $this->input('mode', 'create');

        if ($mode === 'update') {
            return [
                'coach_image' => 'nullable|image|mimes:jpeg,png,jpg',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'address1' => 'required',
                'area' => 'required',
                'city' => 'required|string|max:100',
                'pincode' => 'required',
                'dob' => 'required|date',
                'age' => 'required|numeric|between:10,99',
                'gender' => 'required',
                'document' => 'required',


                'coach_contact_no' => 'required|numeric|digits:10',
                'coach_email' => 'required|email',
            ];
        }

        return [
            //create
            'coach_image' => 'required|image',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address1' => 'required',
            'area' => 'required',
            'city' => 'required|string|max:100',
            'pincode' => 'required',
            'dob' => 'required|date',
            'age' => 'required|numeric|between:10,99',
            'gender' => 'required',
            'document' => 'required',


            'coach_contact_no' => 'required|numeric|digits:10',
            'coach_email' => 'required|email',
            'password' => 'required',
            'id' => 'required',
        ];
    }
}
