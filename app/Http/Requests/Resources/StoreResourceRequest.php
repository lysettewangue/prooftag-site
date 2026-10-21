<?php

declare(strict_types=1);

namespace App\Http\Requests\Resources;

use App\Enums\ResourceRegulatoryStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreResourceRequest extends FormRequest
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
            'regulatory_status' => ['required', Rule::enum(ResourceRegulatoryStatus::class)],
            'authority' => ['nullable', 'string', 'max:191'],
            'issued_on' => ['nullable', 'date'],
            'relates_to_ct' => ['nullable', 'boolean'],
        ];
    }
}
