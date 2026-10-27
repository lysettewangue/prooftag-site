@props([
    'name',
    'label',
    'value' => null,
    'id' => null,
    'help' => null,
    'error' => null,
    'required' => false,
    'disabled' => false,
    'rows' => 5,
])

@php
    $fieldId = $id ?: str_replace(['.', '[', ']'], ['-', '-', ''], $name);
    $errorMessage = $error ?: (isset($errors) ? $errors->first($name) : null);
    $describedBy = trim(($help ? $fieldId.'-help ' : '').($errorMessage ? $fieldId.'-error' : '')) ?: null;
    $currentValue = old($name, $value ?? trim((string) $slot));
@endphp

<div {{ $attributes->only('class') }}>
    <label for="{{ $fieldId }}" class="block text-sm font-bold text-ink">
        {{ $label }}
        @if ($required)<span aria-hidden="true" class="text-status-danger">*</span>@endif
    </label>
    @if ($help)
        <p id="{{ $fieldId }}-help" class="mt-1 text-sm text-muted">{{ $help }}</p>
    @endif
    <textarea
        id="{{ $fieldId }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @required($required)
        @disabled($disabled)
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
        @if ($errorMessage) aria-invalid="true" @endif
        {{ $attributes->except('class')->class([
            'mt-2 block w-full rounded-control border bg-white px-3 py-2 text-ink transition-colors placeholder:text-muted hover:border-brand-azure-600 disabled:cursor-not-allowed disabled:bg-status-disabled-soft disabled:text-status-disabled',
            'border-status-danger' => $errorMessage,
            'border-control-border' => ! $errorMessage,
        ]) }}
    >{{ $currentValue }}</textarea>
    @if ($errorMessage)
        <p id="{{ $fieldId }}-error" class="mt-2 text-sm font-semibold text-status-danger">{{ $errorMessage }}</p>
    @endif
</div>
