<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
            'id' => 'required|integer',
            'category.id' => 'required|integer',
            'category.name' => 'required|string',
            'name' => 'required|string',
            'photoUrls' => 'required|array',
            'photoUrls.*' => 'string',
            'tags' => 'required|array',
            'tags.*.id' => 'required|integer',
            'tags.*.name' => 'required|string',
            'status' => 'required|in:' . implode(',', OrderStatus::toArray()),
        ];
    }
}
