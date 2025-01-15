<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidBudgetContentStructure implements Rule
{
    public function passes($attribute, $value)
    {
        $decodedContent = json_decode($value, true);

        // Validar que sea un array y que cada elemento tenga las claves esperadas
        if (!is_array($decodedContent)) {
            return false;
        }

        foreach ($decodedContent as $item) {
            if (!isset($item['quantity']) || !is_int($item['quantity'])) {
                return false;
            }

            if (!isset($item['details']['name']) || !is_string($item['details']['name'])) {
                return false;
            }

            if (!isset($item['details']['price']) || !is_numeric($item['details']['price'])) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'El contenido no tiene la estructura válida.';
    }
}
