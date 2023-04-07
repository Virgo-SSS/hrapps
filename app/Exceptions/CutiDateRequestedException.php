<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;

class CutiDateRequestedException extends Exception
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        $message = 'You already has a cuti request on ' . $message . '. You can only request cuti once in a period.';
        parent::__construct($message, $code, $previous);
    }

    public function render(): RedirectResponse
    {
        return redirect()->back()->with('swal-error', $this->getMessage());
    }
}
