<?php

declare(strict_types=1);

namespace App\Http\Requests\Seo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpsertRedirectRequest extends FormRequest
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
        /** @var list<string> $hosts */
        $hosts = config('cms.allowed_redirect_hosts', []);

        return [
            'from_host' => ['required', 'string', Rule::in($hosts)],
            'from_path' => ['required', 'string', 'max:2048', 'starts_with:/'],
            'to_path' => ['required', 'string', 'max:2048', 'starts_with:/'],
            'status_code' => ['nullable', Rule::in([301, 302])],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
