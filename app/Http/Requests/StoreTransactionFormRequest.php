<?php

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransactionFormRequest extends FormRequest
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
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|string|in:income,expense,transfer',
            'category_id' => ['required',Rule::exists('categories','id')->where(function (Builder $query) {
                $query->where('is_system', true)
                    ->orWhere('user_id', auth()->id());
            })],
            'date' => 'string',
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'O campo valor é obrigatório.',
            'amount.numeric' => 'O campo valor deve ser um número.',
            'amount.min' => 'O campo valor deve ser maior que zero.',
            'user_id.required' => 'O campo usuário é obrigatório.',
            'user_id.exists' => 'O usuário informado não existe.',
            'type.required' => 'O campo tipo é obrigatório.',
            'type.string' => 'O campo tipo deve ser uma string.',
            'type.in' => 'O campo tipo deve ser um dos seguintes valores: income, expense, transfer.',
            'category_id.required' => 'O campo categoria é obrigatório.',
            'category_id.exists' => 'A categoria informada não existe ou não pertence ao usuário.',
            'description.max' => 'A descrição deve ter no máximo 255 caracteres.'
        ];
    }
}
