{{-- Desktop (navbar) --}}
<flux:navbar.item :href="route('questions.index')" :current="request()->routeIs('questions.*')" wire:navigate>
  {{ __('Foro') }}
</flux:navbar.item>
<flux:navbar.item :href="route('blog.index')" :current="request()->routeIs('blog.*')" wire:navigate>
  {{ __('Blog') }}
</flux:navbar.item>
<flux:navbar.item :href="route('questions.create')" wire:navigate>
  {{ __('Preguntar') }}
</flux:navbar.item>