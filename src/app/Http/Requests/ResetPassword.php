<?php

namespace Squashjedi\Basecamp\App\Http\Requests;

use Request;
use Illuminate\Foundation\Http\FormRequest;

class ResetPassword extends FormRequest
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
            'passwordCurrent' => 'required|passwordMatch',
            'passwordNew' => 'required:required_with:passwordConfirmation|min:6|same:passwordConfirmation'
        ];
    }

    public function messages()
    {
        return [
            'passwordCurrent.required' => 'Current Password is required',
            'passwordNew.min' => 'Must be at least 6 characters',
            'passwordNew.same' => 'Passwords do not match',
            'passwordNew.required' => 'New Password is required'
        ];
    }
}