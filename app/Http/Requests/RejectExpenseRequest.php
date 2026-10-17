<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'comment' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'comment.required' => '差し戻しの理由を入れてください',
            'comment.max' => '差し戻しの理由は500文字までです',
        ];
    }
}
