<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'uuid' => 'required|uuid',
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
            'bank' => 'required|string',
            'norek' => 'required|string',
            'divisi' => 'required|string',
            'posisi' => 'required|string',
            'cuti' => 'required|integer',
            'salary' => 'required|integer',
        ];
    }
}
