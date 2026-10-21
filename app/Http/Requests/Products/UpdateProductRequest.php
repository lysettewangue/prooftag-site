<?php

declare(strict_types=1);

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateProductRequest extends FormRequest
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
            'external_url' => ['nullable', 'url', 'max:2048'],
            'expertise_ids' => ['sometimes', 'array'],
            'expertise_ids.*' => ['integer', 'exists:expertises,id'],
        ];
    }
}
