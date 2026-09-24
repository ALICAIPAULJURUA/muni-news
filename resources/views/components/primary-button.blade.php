<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-sm font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150']) }} style="background: var(--muni-red, #8B0000); min-height:44px; border-radius:2px;" onmouseover="this.style.background='var(--muni-red-dark, #5C0000)'" onmouseout="this.style.background='var(--muni-red, #8B0000)'">
    {{ $slot }}
</button>
