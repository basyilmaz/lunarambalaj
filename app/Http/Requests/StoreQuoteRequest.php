<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'company' => ['nullable', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:150'],
            'product_category' => ['required', 'string', 'max:150'],
            'product' => ['nullable', 'string', 'max:150'],
            'quantity' => ['required', 'integer', 'min:1'],
            'print_needed' => ['required', 'in:yes,no'],
            'wrapping_needed' => ['required', 'in:yes,no'],
            'delivery_city' => ['required', 'string', 'max:120'],
            'message' => ['nullable', 'string', 'max:2000'],
            'attachment' => ['nullable', 'file', 'max:5120', 'mimes:pdf,png,jpg,jpeg,webp,svg'],
            'kvkk' => ['accepted'],
            'website' => ['nullable', 'prohibited'],
            'utm_source' => ['nullable', 'string', 'max:120'],
            'utm_medium' => ['nullable', 'string', 'max:120'],
            'utm_campaign' => ['nullable', 'string', 'max:120'],
            'utm_term' => ['nullable', 'string', 'max:120'],
            'utm_content' => ['nullable', 'string', 'max:120'],
            'gclid' => ['nullable', 'string', 'max:160'],
            'fbclid' => ['nullable', 'string', 'max:160'],
        ];
    }
}

