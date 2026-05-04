<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-lg bg-blue-900 px-4 py-2.5 font-semibold text-xs uppercase tracking-[0.12em] text-white shadow-sm transition hover:bg-blue-800 focus:bg-blue-800 active:bg-slate-950 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
