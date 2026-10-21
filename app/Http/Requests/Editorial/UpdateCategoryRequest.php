<?php

declare(strict_types=1);

namespace App\Http\Requests\Editorial;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateCategoryRequest extends FormRequest
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
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
