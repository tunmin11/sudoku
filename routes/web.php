<?php

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
Route::get('/question', function(){
	return view('sudoku.question');
})->name('question');

Route::resource('/questions','QuestionController');
Route::get('/test', function() {
    dd(json_decode(session('solution'), 1));
});
Route::post('/solution','SudokuSolverController@solveIt')->name('solution');
