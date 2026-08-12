<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactMessageRequest extends FormRequest
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
        return [
            'full_name' => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'subject'   => 'required|string|max:255',
            'message'   => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Full name is required.',
            'full_name.max'      => 'Full name cannot exceed 255 characters.',
            'email.required'     => 'Email address is required.',
            'email.email'        => 'Please enter a valid email address.',
            'subject.required'   => 'Subject is required.',
            'subject.max'        => 'Subject cannot exceed 255 characters.',
            'message.required'   => 'Message field is required.',
        ];
    }
}
