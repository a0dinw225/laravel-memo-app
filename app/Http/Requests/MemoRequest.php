<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'content' => 'required',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     * 
     * @return array
     */
    public function messages(): array
{
    return [
        'content.required' => 'メモ内容は必ず入力してください。'
    ];
}
}
