<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FileType implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

    }

    public function passes($attribute, $file)
    {
        $allowedTypes = ['jpeg', 'png', 'gif'];

        return in_array($file->getClientOriginalExtension(), $allowedTypes);
    }
}
