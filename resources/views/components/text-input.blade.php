@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 px-4 py-2 focus:border-[var(--color-primary-transparent)] focus:ring-[var(--color-primary-foggy)] rounded-md shadow transition-all duration-300']) }}>
