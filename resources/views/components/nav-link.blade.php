@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-[22px] py-1.5 font-semibold text-white bg-white/10 hover:bg-white/15 border-l-2 border-white animation transition duration-250'
            : 'block w-full px-6 py-1.5 text-gray-500 hover:text-gray-200 hover:bg-white/15 animation transition duration-250';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
