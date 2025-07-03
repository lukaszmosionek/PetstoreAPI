<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\OrderStatus; // adjust if necessary

class FindPetsByStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow everyone, customize if needed
    }

    public function rules()
    {
        return [
            'statuses' => ['required', 'array'],
            'statuses.*' => ['in:' . implode(',', OrderStatus::toArray())],
        ];
    }

    public function messages(): array
    {
        return [
            'statuses.*.in' => 'One or more statuses are invalid. Allowed: ' . implode(', ', OrderStatus::toArray()),
        ];
    }
}
