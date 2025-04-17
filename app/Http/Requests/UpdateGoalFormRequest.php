<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGoalFormRequest extends FormRequest
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
            'target_amount' => 'sometimes|numeric|min:0',
            'frequency'     => 'sometimes|in:weekly,monthly',
            'start_date'    => 'sometimes|date',
            'end_date'      => 'sometimes|date|after_or_equal:start_date',
        ];
    }

    /**
     * Get custom error messages for the validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'target_amount.numeric' => 'O campo valor alvo deve ser um número.',
            'target_amount.min' => 'O campo valor alvo deve ser maior ou igual a 0.',
            'frequency.in' => 'O campo frequência deve ser semanal ou mensal.',
            'start_date.date' => 'O campo data de início deve ser uma data válida.',
            'end_date.date' => 'O campo data de término deve ser uma data válida.',
            'end_date.after_or_equal' => 'A data de término deve ser igual ou posterior à data de início.',
        ];
    }
}
