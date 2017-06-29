<?php

namespace Squashjedi\Basecamp\App\Http\Requests;

use Request;
use Illuminate\Foundation\Http\FormRequest;

class UserFormEdit extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . Request::input('id'),
            'password' => 'required_with:passwordConfirmation|min:6|same:passwordConfirmation'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.unique' => 'This email is taken',
            'email.email' => 'Must be a valid email',
            'password.min' => 'Must be at least 6 characters',
            'password.same' => 'Passwords do not match',
            'password.required' => 'Password is required'
        ];
    }
}