<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Support\Facades\Schema;

class PageController extends Controller
{
    public function index()
    {
        // Si no existe la tabla (deploy fresco), evita romper:
        $questions = Schema::hasTable('questions')
            ? Question::with(['category','user'])->latest()->get()
            : collect();

        return view('pages.home', ['questions' => $questions]);
    }
}