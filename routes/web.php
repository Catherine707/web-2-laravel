<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Http\Controllers\PageController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Response;

Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/question/{question}', [QuestionController::class, 'show'])->name('question.show');

Route::middleware(['auth'])->group(function () {
    // Preguntar (crear)
    Route::get('/preguntar', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');

    // Editar / Actualizar
    Route::get('/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');

    // Eliminar
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    // Responder 
    Route::post('/answers/{question}', [AnswerController::class, 'store'])->name('answers.store');

    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::view('dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';


Route::get('/livewire/livewire.min.js', function () {
    $path = base_path('vendor/livewire/flux/dist/flux.min.js');

    if (! file_exists($path)) {
        abort(404);
    }

    return Response::file($path, [
        'Content-Type' => 'application/javascript; charset=UTF-8',
    ]);
});



Route::get('/health', fn () => 'OK '.now());

