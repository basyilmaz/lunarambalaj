<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTrackingEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'event_key' => [
                'required',
                'string',
                'max:100',
                Rule::exists('tracking_events', 'event_key')->where(fn ($query) => $query->where('is_active', true)),
            ],
            'payload' => ['nullable', 'array'],
            'page_path' => ['nullable', 'string', 'max:255'],
        ];
    }
}

