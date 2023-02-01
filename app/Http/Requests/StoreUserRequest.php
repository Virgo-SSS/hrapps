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
            'uuid'      => 'required|uuid',
            'name'      => 'required|string|max:255',
            'password'  => 'required|string|min:6',

            'email'     => 'required|email|unique:users,email',
            'bank'      => ['required','integer', new CheckBank()],
            'norek'     => 'required', 'numeric',

            'divisi'    => 'required|exists:divisi,id',
            'posisi'    => 'required|exists:posisi,id',
            'join_date' => 'required|date',

            'cuti'      => 'required|integer',
            'salary'    => 'required|numeric',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'norek' => preg_replace('-', '', $this->norek),
            'salary' => preg_replace('.', '', $this->salary),
        ]);
    }
}
