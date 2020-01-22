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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/question', function(){
	return view('sudoku.question');
})->name('question');

Route::resource('/questions','QuestionController');
Route::get('/solve','SolutionController@test')->name('solve');
Route::post('/solution','SudokuSolverController@solveIt')->name('solution');