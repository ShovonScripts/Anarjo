<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SslCommerzCallbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tran_id'  => ['required', 'string'],
            'val_id'   => ['nullable', 'string'],
            'amount'   => ['nullable', 'numeric'],
            'currency' => ['nullable', 'string'],
        ];
    }
}
