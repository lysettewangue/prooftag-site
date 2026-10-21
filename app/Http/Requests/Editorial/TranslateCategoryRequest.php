<?php

declare(strict_types=1);

namespace App\Http\Requests\Editorial;

use App\Enums\Locale;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class TranslateCategoryRequest extends FormRequest
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
            'locale' => ['required', Rule::enum(Locale::class)],
            'slug' => ['required', 'string', 'max:191'],
            'name' => ['required', 'string', 'max:191'],
            'meta_title' => ['nullable', 'string', 'max:191'],
            'meta_description' => ['nullable', 'string', 'max:320'],
            'canonical_path' => ['nullable', 'string', 'max:2048'],
        ];
    }
}
