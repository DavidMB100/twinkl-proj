<?php
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IpCheckRule implements ValidationRule
{
    const IP_ADDRESS_BLOCKLIST = [
       '1.1.1.1', '2.2.2.2', '3.3.3.3'
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (in_array($value, self::IP_ADDRESS_BLOCKLIST)) {
            $fail('The :attribute is blocked.');
        }
    }
}