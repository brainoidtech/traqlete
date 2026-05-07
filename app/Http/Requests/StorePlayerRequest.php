<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlayerRequest extends FormRequest
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

        // update
        if ($mode === 'update') {
            return [
                'player_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Optional image upload validation
                'first_name' => 'required|string|max:255',
                'middle_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'address1' => 'required|string|max:255',
                'address2' => 'nullable|string|max:255',
                'area' => 'required|string|max:255',
                'city' => 'required|string|max:100',
                'pincode' => 'required|numeric',
                'dob' => 'required|date',
                'age' => 'required|numeric|min:8',
                'gender' => 'required|string|max:10',
                // 'batch_id' => 'required|numeric',
                'aadhar_number' => 'required',
                'player_contact_no' => 'required|numeric|digits:10',
                'player_email' => 'required|email',

                // Parent data validation
                'parent' => 'array',
                'parent.*.parent_name' => 'required|string|max:255',
                'parent.*.relation' => 'required|string|max:255',
                'parent.*.parent_contact' => 'required|numeric|digits:10',
                'parent.*.parent_email' => 'required|email',
            ];
        }

        // create
        return [
            'player_image' => 'required|image',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address1' => 'required',

            'area' => 'required',
            'city' => 'required|string|max:100',
            'pincode' => 'required|numeric',
            'dob' => 'required|date',
            'age' => 'required|numeric|min:8',
            'gender' => 'required',
            'batch_id' => 'required|numeric',
            'aadhar_number' => 'required',
            'player_id' => 'required',
            'player_contact_no' => 'required|numeric|digits:10',
            'player_email' => 'required|email',

            'hoid' => 'required',
            'school_name' => 'nullable|string',
            'foot' => 'required',
            'inch' => 'required',
            'weight' => 'required',

            'parent.*.parent_name' => 'required|string',
            'parent.*.relation' => 'required|string',
            'parent.*.parent_contact' => 'required|numeric|digits:10',
            'parent.*.parent_email' => 'required|email',
        ];
    }

   

    public function messages(): array
    {
        return [
            // Parent contact
            'parent.*.parent_contact.digits' => 'Each parent contact number must be exactly 10 digits.',
            'parent.*.parent_contact.numeric' => 'Parent contact number must contain only numbers.',

            // Parent name
            'parent.*.parent_name.required' => 'Parent name is required.',

            // Relation
            'parent.*.relation.required' => 'Relation is required.',

            // Email
            'parent.*.parent_email.email' => 'Please enter a valid parent email address.',
        ];
    }
}
