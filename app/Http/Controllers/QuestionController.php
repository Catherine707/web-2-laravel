<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class QuestionController extends Controller
{
    // Ver las preguntas
    public function show(Question $question)
    {
        $question->load('answers', 'category', 'user');

        return view('questions.show', [
            'question' => $question,
        ]);
    }

    // Mostrar formulario
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('questions.create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',         
            'category_id' => 'required|exists:categories,id',
        ]);

        $question = new Question();
        $question->title       = $data['title'];
        $question->content     = $data['content'];       
        $question->category_id = $data['category_id'];
        $question->user_id     = Auth::id();
        $question->save();

        return redirect()
            ->route('question.show', $question)
            ->with('status', '¡Pregunta publicada exitosamente!');
    }

    // Editar pregunta
    public function edit(Question $question)
    {
        $this->authorize('update', $question);

        $categories = Category::orderBy('name')->get();

        return view('questions.edit', [
            'question'   => $question,
            'categories' => $categories,
        ]);
    }

    // Actualizar
    public function update(Request $request, Question $question)
    {
        $this->authorize('update', $question);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $question->title       = $validated['title'];
        $question->content     = $validated['content'];
        $question->category_id = $validated['category_id'];
        $question->save();

        return redirect()
            ->route('question.show', $question)
            ->with('status', '¡Pregunta actualizada!');
    }

    // Eliminar
    public function destroy(Question $question)
    {
        $this->authorize('delete', $question);

        $question->delete();

        return redirect()
            ->route('home')
            ->with('status', 'Pregunta eliminada');
    }
}
