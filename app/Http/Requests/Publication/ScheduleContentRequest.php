<?php

declare(strict_types=1);

namespace App\Http\Requests\Publication;

use Illuminate\Foundation\Http\FormRequest;

final class ScheduleContentRequest extends FormRequest
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
            'scheduled_for' => ['required', 'date', 'after:now'],
        ];
    }
}
