<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGoalFormRequest extends FormRequest
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
            'category_id'   => 'required|exists:categories,id|unique:goals,category_id',
            'target_amount' => 'required|numeric|min:0',
            'frequency'     => 'required|in:weekly,monthly',
            'start_date'    => 'required|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
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
            'category_id.required' => 'O campo categoria é obrigatório.',
            'category_id.exists' => 'A categoria selecionada não existe.',
            'category_id.unique' => 'Já existe uma meta cadastrada para esta categoria.',
            'target_amount.required' => 'O campo valor alvo é obrigatório.',
            'target_amount.numeric' => 'O campo valor alvo deve ser um número.',
            'target_amount.min' => 'O campo valor alvo deve ser maior ou igual a 0.',
            'frequency.required' => 'O campo frequência é obrigatório.',
            'frequency.in' => 'O campo frequência deve ser semanal ou mensal.',
            'start_date.required' => 'O campo data de início é obrigatório.',
            'start_date.date' => 'O campo data de início deve ser uma data válida.',
            'end_date.date' => 'O campo data de término deve ser uma data válida.',
            'end_date.after_or_equal' => 'A data de término deve ser igual ou posterior à data de início.',
            'end_date.required' => 'O campo data de término é obrigatório.',
        ];
    }
}
