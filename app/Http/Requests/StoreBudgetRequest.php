<?php

namespace App\Http\Requests;

use App\Rules\ValidBudgetContentStructure;
use Illuminate\Foundation\Http\FormRequest;

class StoreBudgetRequest extends FormRequest
{   
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'client_id' => 'nullable|integer|exists:clients,id',
            'content' => ["sometimes", "json", new ValidBudgetContentStructure()],
            'state' => 'required|in:draft,approved,rejected',
            'discount' => 'sometimes|integer',
            'taxes' => 'required|integer'
        ];
    }
}
