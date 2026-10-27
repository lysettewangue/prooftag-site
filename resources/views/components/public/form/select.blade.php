@props([
    'name',
    'label',
    'options' => [],
    'selected' => null,
    'placeholder' => null,
    'id' => null,
    'help' => null,
    'error' => null,
    'required' => false,
    'disabled' => false,
])

@php
    $fieldId = $id ?: str_replace(['.', '[', ']'], ['-', '-', ''], $name);
    $errorMessage = $error ?: (isset($errors) ? $errors->first($name) : null);
    $describedBy = trim(($help ? $fieldId.'-help ' : '').($errorMessage ? $fieldId.'-error' : '')) ?: null;
    $currentValue = old($name, $selected);
@endphp

<div {{ $attributes->only('class') }}>
    <label for="{{ $fieldId }}" class="block text-sm font-bold text-ink">
        {{ $label }}
        @if ($required)<span aria-hidden="true" class="text-status-danger">*</span>@endif
    </label>
    @if ($help)
        <p id="{{ $fieldId }}-help" class="mt-1 text-sm text-muted">{{ $help }}</p>
    @endif
    <select
        id="{{ $fieldId }}"
        name="{{ $name }}"
        @required($required)
        @disabled($disabled)
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
        @if ($errorMessage) aria-invalid="true" @endif
        {{ $attributes->except('class')->class([
            'mt-2 block min-h-control w-full rounded-control border bg-white px-3 py-2 text-ink transition-colors hover:border-brand-azure-600 disabled:cursor-not-allowed disabled:bg-status-disabled-soft disabled:text-status-disabled',
            'border-status-danger' => $errorMessage,
            'border-control-border' => ! $errorMessage,
        ]) }}
    >
        @if ($placeholder)
            <option value="" @selected($currentValue === null || $currentValue === '')>{{ $placeholder }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) $currentValue === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
    @if ($errorMessage)
        <p id="{{ $fieldId }}-error" class="mt-2 text-sm font-semibold text-status-danger">{{ $errorMessage }}</p>
    @endif
</div>
