@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center border-b-2 border-blue-700 px-1 pt-1 text-sm font-semibold leading-5 text-slate-900 focus:border-blue-800 focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium leading-5 text-slate-500 hover:border-slate-300 hover:text-slate-900 focus:border-slate-300 focus:outline-none focus:text-slate-900 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
