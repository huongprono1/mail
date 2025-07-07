<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'gap-2 inline-flex align-middle items-center px-4 py-2 bg-blue-900 dark:bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white dark:text-slate-100 uppercase tracking-widest hover:bg-blue-950 dark:hover:bg-slate-900 focus:bg-slate-700 dark:focus:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
