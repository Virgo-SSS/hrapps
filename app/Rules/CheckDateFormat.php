<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckDateFormat implements Rule
{
    private string $format;
    private string $type;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $format,string $type)
    {
        $this->format = $format;
        $this->type = $type;
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
        if ($this->format == 'Y-m-d') {
            $pattern = [
                'daterange' => '/^(\d{4}-\d{2}-\d{2}) - (\d{4}-\d{2}-\d{2})$/',
            ];
        }

        return preg_match($pattern[$this->type], $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute format is invalid.';
    }
}
