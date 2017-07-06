<?php

namespace Squashjedi\Basecamp\App\Http\Requests;

use Request;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordForgot extends FormRequest
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
          'password_new' => 'required_with:password_confirmation|min:6|same:password_confirmation'
        ];
    }

    public function messages()
    {
        return [
            'password_new.min' => 'Must be at least 6 characters',
            'password_new.same' => 'Passwords do not match',
            'password_new.required' => 'Required'
        ];
    }
}