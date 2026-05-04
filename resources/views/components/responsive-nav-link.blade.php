@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full border-l-4 border-[#2f5ea8] bg-[#edf3ff] ps-3 pe-4 py-2 text-start text-base font-semibold text-slate-900 focus:border-[#244a83] focus:bg-[#e6eefc] focus:text-slate-950 focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full border-l-4 border-transparent ps-3 pe-4 py-2 text-start text-base font-medium text-slate-600 hover:border-[#d8c59d] hover:bg-white/70 hover:text-slate-900 focus:border-[#b7c7e8] focus:bg-white/70 focus:text-slate-900 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
