@props(['type' => 'text'])

<input type="{{ $type }}"
       {{ $attributes->merge(['class' => 'w-full rounded-lg border border-outline-variant bg-white px-md py-2.5 text-body-md text-on-surface outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20']) }}>
