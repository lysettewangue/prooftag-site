<?php

declare(strict_types=1);

namespace App\Http\Requests\Contact;

use App\Enums\Locale;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class SubmitContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191'],
            'phone' => ['nullable', 'string', 'max:64'],
            'subject' => ['required', 'string', 'max:191'],
            'message' => ['required', 'string', 'max:5000'],
            'locale' => ['nullable', Rule::enum(Locale::class)],
            'website' => ['nullable', 'string', 'max:191'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => (string) __('ui.contact.name'),
            'email' => (string) __('ui.contact.email'),
            'phone' => (string) __('ui.contact.phone'),
            'subject' => (string) __('ui.contact.subject'),
            'message' => (string) __('ui.contact.message'),
        ];
    }
}
