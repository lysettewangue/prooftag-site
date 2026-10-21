<?php

declare(strict_types=1);

namespace App\Http\Requests\Centres;

use Illuminate\Foundation\Http\FormRequest;

final class ImportCentreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'region_id' => ['required', 'integer', 'exists:regions,id'],
            'operator_name' => ['required', 'string', 'max:191'],
            'city' => ['required', 'string', 'max:191'],
            'name' => ['nullable', 'string', 'max:191'],
            'slug' => ['nullable', 'string', 'max:191'],
            'phone' => ['nullable', 'string', 'max:64'],
            'email' => ['nullable', 'email', 'max:191'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'address_line' => ['nullable', 'string', 'max:191'],
            'body' => ['nullable', 'string'],
        ];
    }
}
