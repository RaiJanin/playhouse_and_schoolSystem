@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border border-[var(--color-primary)] px-4 py-2 focus:border-2 rounded-md shadow transition-all duration-300']) }}>
