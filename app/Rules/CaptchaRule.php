<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CaptchaRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strtolower($value) !== strtolower(session('captcha'))) {
            $fail('Kode captcha salah. Silakan coba lagi.');
        }
    }
}
