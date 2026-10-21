<?php

declare(strict_types=1);

namespace App\Http\Requests\Editorial;

use App\Enums\PostKind;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdatePostRequest extends FormRequest
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
            'kind' => ['sometimes', Rule::enum(PostKind::class)],
            'category_ids' => ['sometimes', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'product_ids' => ['sometimes', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],
            'expertise_ids' => ['sometimes', 'array'],
            'expertise_ids.*' => ['integer', 'exists:expertises,id'],
        ];
    }
}
