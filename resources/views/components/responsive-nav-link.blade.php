@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full border-l-4 border-blue-700 bg-blue-50 ps-3 pe-4 py-2 text-start text-base font-semibold text-slate-900 focus:border-blue-800 focus:bg-blue-50 focus:text-slate-950 focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full border-l-4 border-transparent ps-3 pe-4 py-2 text-start text-base font-medium text-slate-600 hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900 focus:border-slate-300 focus:bg-slate-50 focus:text-slate-900 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
