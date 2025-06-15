<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class editAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tel' => ['required', 'regex:/^\d{2,4}-\d{2,4}$/'], // ハイフン付きの電話番号を許可
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'tel.required' => '電話番号を入力してください',
            'tel.regex' => '電話番号はハイフン付きで入力してください',
            'address.required' => '住所を入力してください',
        ];
    }
}
