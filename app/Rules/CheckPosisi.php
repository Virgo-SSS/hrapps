<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CheckPosisi implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $divisi_id = request()->input('divisi_id');

        if(!is_null($divisi_id)) {
            $posisi = DB::table('posisi')->where('id', $value)->first();

            if($posisi && $posisi->divisi_id != $divisi_id){
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected posisi is not available for selected divisi.';
    }
}
