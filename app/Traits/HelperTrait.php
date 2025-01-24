<?php

namespace App\Traits;

use Illuminate\Support\Str;
use InvalidArgumentException;

trait HelperTrait
{
    public function arrayChangeKeyCase($data, string $case = 'snake')
    {
        if (!in_array($case, ['snake', 'camel'])) {
            throw new InvalidArgumentException("O parâmetro \$case deve ser 'snake' ou 'camel'.");
        }

        $output = [];

        foreach ($data as $key => $value) {
            $key = Str::{$case}($key);

            if (is_array($value)) {
                $output[$key] = $this->arrayChangeKeyCase($value, $case);
            } else {
                $output[$key] = $value;
            }
        }

        return $output;
    }
}
