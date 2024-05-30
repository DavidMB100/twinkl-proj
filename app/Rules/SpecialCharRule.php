<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SpecialCharRule implements ValidationRule
{
    const SPECIAL_CHARS = '/[#$%^&*()+=\-\[\]\';,\/{}|":<>?~\\\\]/';

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (preg_match(self::SPECIAL_CHARS, $value)) {
            $fail('The :attribute has disallowed characters.');
        }
    }
}
