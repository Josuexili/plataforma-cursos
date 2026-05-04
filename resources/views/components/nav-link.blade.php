@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center border-b-2 border-[#2f5ea8] px-1 pt-1 text-sm font-semibold leading-5 text-slate-900 focus:border-[#244a83] focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium leading-5 text-slate-500 hover:border-[#d8c59d] hover:text-slate-900 focus:border-[#b7c7e8] focus:outline-none focus:text-slate-900 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
