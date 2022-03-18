<?php
use App\Models\Notes;
/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    echo Notes::all();
});

$router->group(['prefix'=>'api'], function () use ($router){
    $router->get('notes', 'NotesController@allNotes');
    $router->get('note/', 'NotesController@oneNote');
    $router->delete('note/delete/{id}', 'NotesController@deleteNote');
    $router->post('note/add', 'NotesController@addNote');
    $router->put('note/toFavourite/', 'NotesController@toFavourite');
    $router->put('note/update', 'NotesController@updateNote');
    $router->get('notes/filter/favorites', 'NotesController@filterByFavorites');
    $router->get('notes/filter/date', 'NotesController@filterByDate');
});
