<x-forum.layouts.home>
    <div class="max-w-3xl mx-auto py-6">
        <h1 class="text-2xl font-bold mb-4">Editar pregunta</h1>

        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('questions.update', $question) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium">CategorÃ­a</label>
                <select name="category_id" class="w-full border rounded p-2" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id',$question->category_id)==$cat->id)>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium">TÃ­tulo</label>
                <input name="title" value="{{ old('title',$question->title) }}" class="w-full border rounded p-2" required />
            </div>

            <div>
                <label class="block text-sm font-medium">Contenido</label>
                <textarea name="content" rows="6" class="w-full border rounded p-2" required>{{ old('content',$question->content) }}</textarea>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('question.show',$question) }}" class="px-4 py-2 border rounded">Cancelar</a>
                <button class="px-4 py-2 bg-black text-white rounded">Guardar</button>
            </div>
        </form>
    </div>
</x-forum.layouts.home>


