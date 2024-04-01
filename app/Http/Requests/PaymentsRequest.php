<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'type' => 'required|in:cash,gcash',
            'payment_for' => 'required|in:monthly,bi-monthly,6-months,1-year,Annual-Fee',
            'transaction_code' => $this->input('type') === 'gcash' ? 'required' : 'nullable',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'member_id' => 'Member',
            'amount' => 'Amount',
            'payment_date' => 'Payment Date',
            'type' => 'Payment Option',
            'payment_for' => 'Payment Plan',
            'transaction_code' => 'Transaction Code',
            'Annual-Fee' => 'Annual Fee',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
