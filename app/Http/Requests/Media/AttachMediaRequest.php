<?php

declare(strict_types=1);

namespace App\Http\Requests\Media;

use App\Domain\Media\MediaRules;
use Illuminate\Foundation\Http\FormRequest;

final class AttachMediaRequest extends FormRequest
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
        $mimes = implode(',', MediaRules::allowedMimes());
        $maxKb = (int) ceil(MediaRules::maxBytes() / 1024);

        return [
            'file' => ['required', 'file', 'mimetypes:'.$mimes, 'max:'.$maxKb],
            'collection' => ['required', 'string', 'max:64'],
            'alt.fr' => ['nullable', 'string', 'max:255'],
            'alt.en' => ['nullable', 'string', 'max:255'],
            'rights' => ['nullable', 'string', 'max:255'],
        ];
    }
}
