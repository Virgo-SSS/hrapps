<?php

namespace App\Http\Requests;

use App\Rules\CheckBank;
use App\Rules\CheckPosisi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'uuid'                  => ['required', Rule::unique('users', 'uuid')->ignore($this->route('user')->id)],
            'name'                  => 'required|string|max:255',
            'email'                 => ['required','email',Rule::unique('users', 'email')->ignore($this->route('user')->id)],

            'bank'                  => ['required','integer', new CheckBank()],
            'bank_account_number'   => 'required|numeric',

            'divisi_id'             => 'required|integer|exists:divisi,id',
            'posisi_id'             => ['required','integer','exists:posisi,id', new CheckPosisi()],
            'join_date'             => 'required|date',

            'cuti'                  => 'required|integer',
            'salary'                => 'required|numeric',

            'role_id'               => 'required|integer|exists:roles,id',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'salary' => str_replace('.', '', $this->salary),
        ]);
    }
}
