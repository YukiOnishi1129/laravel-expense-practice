<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'integer', 'min:1', 'max:1000000'],
            'used_on' => ['required', 'date', 'before_or_equal:today'],
            'description' => ['required', 'string', 'max:255'],
            'receipt' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => '金額を入れてください',
            'amount.integer' => '金額は数字で入れてください',
            'amount.min' => '金額は1円以上で入れてください',
            'amount.max' => '金額は1,000,000円までです',
            'used_on.required' => '使った日を入れてください',
            'used_on.date' => '使った日は日付で入れてください',
            'used_on.before_or_equal' => '使った日に未来の日付は入れられません',
            'description.required' => '内容を入れてください',
            'description.max' => '内容は255文字までです',
            'receipt.image' => '領収書は画像を選んでください',
            'receipt.max' => '領収書の画像は2MBまでです',
        ];
    }
}
