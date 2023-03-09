<?php

namespace App\Http\Requests;

use App\Rules\CheckDateCuti;
use App\Rules\CheckDateFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCutiRequest extends FormRequest
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
            'date' => ['required', new  CheckDateFormat('Y-m-d','daterange'), new CheckDateCuti()],
            'reason' => 'required|string',

            'head_of_division' => 'required|integer|exists:users,id',
            'head_of_department' => 'required|integer|exists:users,id',
        ];
    }
}
