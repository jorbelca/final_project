<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidBudgetContentStructure implements Rule
{
    public function passes($attribute, $value)
    {


        //  dd($value);
        if (!is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!is_object($item)) {
                return false;
            }
            if (!isset($item['quantity']) || !is_numeric($item['quantity'])) {
                return false;
            }

            if (!isset($item['description']) || !is_string($item['description'])) {
                return false;
            }

            if (!isset($item['cost']) || !is_numeric($item['cost'])) {
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
