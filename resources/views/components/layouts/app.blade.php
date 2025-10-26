{{-- resources/views/components/layouts/app.blade.php --}}
<x-layouts.app.header />

<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>



