<?php

namespace App\Http\Requests;

use App\Rules\CheckBank;
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
            'uuid'                  => 'required|unique:users,uuid',
            'name'                  => 'required|string|max:255',
            'password'              => 'required|string|min:6',
            'email'                 => 'required|email|unique:users,email',

            'bank'                  => ['required','integer', new CheckBank()],
            'bank_account_number'   => 'required|numeric',

            'divisi_id'             => 'required|integer|exists:divisi,id',
            'posisi_id'             => 'required|integer|exists:posisi,id',
            'join_date'             => 'required|date',

            'cuti'                  => 'required|integer',
            'salary'                => 'required|numeric',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'salary' => str_replace('.', '', $this->salary),
        ]);
    }

}
