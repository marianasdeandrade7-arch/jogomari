<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Character;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Rota para a página de criação/seleção de personagem
Route::get('/create', function () {
    $characters = Character::all();
    return view('create', compact('characters'));
})->name('create');

// Recebe POST do formulário de criação — salva os dados na session e redireciona para a página da lore
Route::post('/create', function (Request $request) {
    $data = $request->only(['name','level','vida','poder','xp','ataque','defesa','image']);
    // garante level = 1 (firme)
    $data['level'] = 1;
    session(['new_character' => $data]);
    return redirect()->route('lore');
})->name('create.store');

// Página separada que mostra a lore/introdução (leva os dados da session)
Route::get('/lore', function () {
    $character = session('new_character', null);
    return view('lore', compact('character'));
})->name('lore');

// Rota que finaliza e persiste o personagem no banco (usada a partir da página de lore)
Route::post('/create/save', function (Request $request) {
    $data = session('new_character', null);
    if (!$data) {
        return redirect()->route('create')->with('error', 'Dados do personagem não encontrados.');
    }
    // persiste no DB
    $char = Character::create($data);
    // guarda personagem criado na session (usado pela página de batalha)
    session(['player' => $char->toArray()]);
    // limpa dados temporários de criação
    session()->forget('new_character');
    // redireciona diretamente para as fases (battle)
    return redirect()->route('battle');
})->name('create.save');

// Rota para a página de batalha com 3 fases
Route::get('/battle', function () {
    // preferir 'player' (personagem recém-criado); fallback para dados temporários se estiverem presentes
    $character = session('player', session('new_character', null));
    return view('battle', compact('character'));
})->name('battle');
