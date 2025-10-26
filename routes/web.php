<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

use App\Http\Controllers\PageController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;



// Home (lista de últimas preguntas en portada)
Route::get('/', [PageController::class, 'index'])->name('home');

// Foro (listado y detalle)
Route::get('/foro', [QuestionController::class, 'index'])->name('questions.index');
Route::get('/question/{question}', [QuestionController::class, 'show'])->name('question.show');

// Dashboard (requiere autenticación + email verificado)
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Crear pregunta
    Route::get('/preguntar', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');

    // Editar / Actualizar pregunta
    Route::get('/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');

    // Eliminar pregunta
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    // Responder pregunta
    Route::post('/answers/{question}', [AnswerController::class, 'store'])->name('answers.store');

    // Settings (Livewire)
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});


if (app()->environment('local')) {

    Route::get('/__health', function () {
        return [
            'sqlite_path'   => config('database.connections.sqlite.database'),
            'sqlite_exists' => file_exists(config('database.connections.sqlite.database')),
            'questions_tbl' => Schema::hasTable('questions'),
        ];
    })->name('__health');


    Route::get('/livewire/livewire.min.js', function () {
        $path = public_path('flux/flux.min.js');
        abort_unless(file_exists($path), 404);

        return response()->file($path, [
            'Content-Type' => 'application/javascript; charset=UTF-8',
        ]);
    })->name('livewire.shim');
}


require __DIR__ . '/auth.php';