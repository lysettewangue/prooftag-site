@props([
    'name',
    'size' => 20,
])

@php
    $svgAttributes = $attributes->getAttributes();
    $svgAttributes['width'] = (string) $size;
    $svgAttributes['height'] = (string) $size;
    $svgAttributes['class'] = trim('lucide lucide-'.$name.' shrink-0 '.($svgAttributes['class'] ?? ''));
@endphp

{{ \App\Support\LucideIcon::svg($name, $svgAttributes) }}
