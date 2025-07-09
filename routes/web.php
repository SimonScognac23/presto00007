<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\RevisorController;

use App\Http\Livewire\WarhammerStory;








//--------------------  ROTTE PUBBLICHE-------------------------------------------------------------------------------------------------------

Route::get('/', [PublicController::class, 'homepage'])->name('homepage');




///  USER STORY 10
Route::get('/search/article', [PublicController::class, 'searchArticles'])->name('article.search');




//    USER STORY 4
//   cambio lingua
Route::post('/lingua/{lang}', [PublicController::class, 'setLanguage'])->name('setLocale');








//-----------------------------ARTICOLI-----------------------------------------------------------------------------------------------------------------  




Route::get('/create/article', [ArticleController::class, 'create'])->name('create.article')->middleware('auth');




Route::get('/article/index', [ArticleController::class, 'index'])->name('article.index');



// Rotta parametrica (per passare il riferimento dell'articolo)
// posso mettere o {article} oppure {id} è la stessa cosa, passerà sempre e comunque id
Route::get('show/article/{article}', [ArticleController::class, 'show'])->name('article.show'); // Niente middleware affinché tutti gli utenti che navigano nel sito vedano la pagina




// Rotta parametrica il cui parametro è Category
Route::get('/category/{category}', [ArticleController::class, 'byCategory'])->name('byCategory');





//-----------------------REVISORE --- USER STORY 3-------------------------------------------------------------------------------------------------------------------------------------------------------



Route::get('/revisor/index', [RevisorController::class, 'index'])->middleware('isRevisor')->name('revisor.index');



// Abbiamo utilizzato il metodo patch per fare una rotta che accetta un parametro article.

// In HTTP, una rotta PATCH serve ad aggiornare una risorsa esistente in modo parziale. Questo significa che puoi inviare una richiesta 
// PATCH con solo i dati che vuoi modificare, e Laravel aggiornerà la risorsa nel database di conseguenza

// Le rotte PATCH sono utili quando si vuole aggiornare solo uno o due campi di una risorsa, invece di dover inviare l'intero oggetto con tutti i dati
Route::patch('/accept/{article}', [RevisorController::class, 'accept'])->name('accept');



Route::patch('/reject/{article}', [RevisorController::class, 'reject'])->name('reject');




// Rotta per diventare revisor
Route::get('/revisor/request', [RevisorController::class, 'becomeRevisor'])->middleware('auth')->name('become.revisor');




//Rotta make revisor
Route::get('/make/revisor/{user}', [RevisorController::class, 'makeRevisor'])->name('make.revisor');





//-------------WARHAMMER STORY--------------------------------------------------------------------------------------------------------------------------------------------------

//Rotta Warhammer Story
Route::view('/warhammer/story', 'livewire.warhammer-story-page')->name('warhammer.story');





