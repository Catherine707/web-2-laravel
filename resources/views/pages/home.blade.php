{{-- resources/views/pages/home.blade.php --}}
<x-forum.layouts.home>
  <ul class="divide-y divide-gray-100">
    @forelse($questions as $question)
      @php
        $cat     = $question->category ?? null;
        $user    = $question->user ?? null;
        $color   = $cat?->color ?: '#6b7280';
        $catName = $cat?->name ?: 'Sin categoría';
        $userName= $user?->name ?: 'Anónimo';
        $created = optional($question->created_at)?->diffForHumans() ?: '';
      @endphp

      <li class="flex justify-between gap-4 py-4">
        <div class="flex gap-4">
          <div class="size-8 rounded-full flex items-center justify-center" style="background-color: {{ $color }};">
            <x-forum.logo class="h-6 text-white" />
          </div>
          <div class="flex-auto">
            <p class="text-sm font-semibold text-gray-900">
              <a href="{{ route('question.show', $question) }}" class="hover:underline">
                {{ $question->title ?? 'Sin título' }}
              </a>
            </p>
            <p class="mt-1 text-xs text-gray-500">{{ $user?->name ?? 'Anónimo' }}</p>
          </div>
        </div>

        <div class="hidden sm:flex sm:flex-col sm:items-end">
          <p class="text-sm text-gray-900">{{ $cat?->name ?? 'Sin categoría' }}</p>
          @if(optional($question->created_at)?->diffForHumans())
            <p class="mt-1 text-xs text-gray-500">{{ $question->created_at->diffForHumans() }}</p>
          @endif
        </div>
      </li>
    @empty
      <li class="py-8 text-center text-sm text-gray-500">
        Todavía no hay preguntas. ¡Sé la primera en <a class="underline" href="{{ route('questions.create') }}">preguntar</a>!
      </li>
    @endforelse
  </ul>
</x-forum.layouts.home>



