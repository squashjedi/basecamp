<?php

namespace Squashjedi\Basecamp\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserForm extends FormRequest
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
            'email' => 'required|unique:users,email|email',
            'password' => 'required|min:6|same:passwordConfirmation',
            'passwordConfirmation' => 'required|min:6'
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