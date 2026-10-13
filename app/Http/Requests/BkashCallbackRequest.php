<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BkashCallbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'paymentID' => ['required', 'string'],
            'status' => ['required', 'string'],
        ];
    }
}
