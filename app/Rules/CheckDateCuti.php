<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class CheckDateCuti implements Rule
{
    private string $message = '';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $date = explode(' ', $value);
        $from = $date[0];
        $to = $date[2];

        $from = strtotime($from);
        $to = strtotime($to);

        if($from > $to) {
            $this->message = 'Leave date is not valid';
            return false;
        }

        if($from <= strtotime(Carbon::now()->format('Y-m-d'))) {
            $this->message = 'Leave date must be greater than today';
            return false;
        };

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
