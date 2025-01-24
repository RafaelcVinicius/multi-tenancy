<?php

namespace App\Traits;

trait SensitiveDataTrait
{
    public function hideSensitiveData(string $input, int $keepInStart, int $keepInTrail, string $mask = '*'): string
    {
        if ($input === '') {
            return '';
        }

        $keepInStart = max(0, $keepInStart);
        $keepInTrail = max(0, $keepInTrail);

        $inputLength = mb_strlen($input);

        if ($keepInStart + $keepInTrail >= $inputLength) {
            return $input;
        }

        $begin = '';
        $end = '';

        if ($keepInStart > 0)
            $begin = mb_substr($input, 0, $keepInStart);

        if ($keepInTrail > 0)
            $end = mb_substr($input, -$keepInTrail);

        $maskLength = $inputLength - ($keepInStart + $keepInTrail);

        $maskPart = str_repeat($mask, $maskLength);

        return $begin . $maskPart . $end;
    }
}
