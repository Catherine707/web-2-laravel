<x-forum.layouts.app>
    <div class="max-w-3xl mx-auto py-10 px-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-100">Crear pregunta</h1>

        @if ($errors->any())
            <div class="mb-4 text-red-500 bg-red-100/10 border border-red-500/30 rounded-md p-3">
                <ul class="list-disc ml-5 text-sm">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('questions.store') }}" class="space-y-6">
            @csrf

            <!-- CategorÃ­a -->
            <div>
                <label class="block text-sm font-medium text-gray-200 mb-2">CategorÃ­a</label>
                <div class="relative">
                    <select name="category_id"
                            class="w-full rounded-md border border-gray-600 bg-white text-gray-900 px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-[#6a0f1c]"
                            style="color-scheme: light"
                            required>
                        <option value="">-- Selecciona categorÃ­a --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id')==$cat->id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-500"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-200 mb-2">TÃ­tulo</label>
                <input name="title"
                        value="{{ old('title') }}"
                        class="w-full rounded-md border border-gray-600 bg-white text-gray-900 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#6a0f1c]"
                        required />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-200 mb-2">Contenido</label>
                <textarea name="content" rows="6"
                            class="w-full rounded-md border border-gray-600 bg-white text-gray-900 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#6a0f1c]"
                            required>{{ old('content') }}</textarea>
            </div>

            <!-- Botones -->
            <div class="flex gap-3">
                <a href="{{ route('home') }}"
                    class="px-4 py-2 border border-gray-400 text-gray-200 rounded-md hover:bg-gray-800 transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-[#6a0f1c] hover:bg-[#8b1e2d] text-white font-semibold rounded-md transition">
                    Publicar
                </button>
            </div>
        </form>
    </div>
</x-forum.layouts.app>




