<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;

class CutiRequestStillProcessingException extends Exception
{
    public function __construct(string $message = "You already has a pending cuti request Please wait for the approval.", int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render(): RedirectResponse
    {
        return redirect()->back()->with('swal-warning', $this->getMessage());
    }
}
